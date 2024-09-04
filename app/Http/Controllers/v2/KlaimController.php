<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Halim\EKlaim\Builders\BodyBuilder;
use Halim\EKlaim\Services\EklaimService;
use Halim\EKlaim\Controllers\GroupKlaimController;

class KlaimController extends Controller
{
    /**
     * new klaim method
     * 
     * @param \Halim\EKlaim\Http\Requests\NewKlaimRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * */
    public function new(\Halim\EKlaim\Http\Requests\NewKlaimRequest $request)
    {
        BodyBuilder::setMetadata('new_claim');
        BodyBuilder::setData([
            "nomor_sep"     => $request->nomor_sep,
            "nomor_kartu"   => $request->nomor_kartu,
            "nomor_rm"      => $request->nomor_rm,
            "nama_pasien"   => $request->nama_pasien,
            "tgl_lahir"     => $request->tgl_lahir,
            "gender"        => $request->gender
        ]);

        $response = EklaimService::send(BodyBuilder::prepared());

        if ($response->getStatusCode() == 200) {
            $response_data = $response->getData();
            $this->storeInacbgKlaimBaru2(
                $request->no_rawat,
                $request->nomor_sep,
                $response_data->response->patient_id,
                $response_data->response->admission_id,
                $response_data->response->hospital_admission_id
            );
        } else {
            \Log::channel(config('eklaim.log_channel'))->error("Error while creating new klaim", json_decode(json_encode($response->getData()), true));

            BodyBuilder::setMetadata('get_claim_data');
            BodyBuilder::setData([
                "nomor_sep" => $request->nomor_sep
            ]);

            $klaim_data = EklaimService::send(BodyBuilder::prepared());
            $klaim_data = $klaim_data->getData();

            $this->storeInacbgKlaimBaru2(
                $request->no_rawat,
                $request->nomor_sep,
                $klaim_data->response->data->patient_id,
                $klaim_data->response->data->admission_id,
                $klaim_data->response->data->hospital_admission_id
            );
        }

        return $response;
    }

    /**
     * set klaim data method
     * 
     * @param string $sep
     * @param \Halim\EKlaim\Http\Requests\SetKlaimDataRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * */
    public function set($sep, \Halim\EKlaim\Http\Requests\SetKlaimDataRequest $request)
    {
        // ==================================================== NEW KLAIM PROCESS
        
        if (!\App\Models\InacbgKlaimBaru2::where('no_sep', $sep)->exists()) {
            $bridging_sep = \App\Models\BridgingSep::with('pasien')->where('no_sep', $sep)->first();
            $this->new(new \Halim\EKlaim\Http\Requests\NewKlaimRequest([
                'nomor_sep'     => $sep,
                'nomor_kartu'   => $bridging_sep->no_kartu,
                'nomor_rm'      => $bridging_sep->pasien->no_rkm_medis,
                'no_rawat'      => $bridging_sep->no_rawat,
                'nama_pasien'   => $bridging_sep->pasien->nm_pasien,
                'tgl_lahir'     => $bridging_sep->pasien->tgl_lahir,
                'gender'        => $bridging_sep->pasien->jk
            ]));
        }

        // ==================================================== PARSE DATA

        // random no_ik from rsia_coder_nik
        $required = [
            "nomor_sep"     => $sep,
            "coder_nik"     => $request->coder_nik ?? \App\Models\RsiaCoderNik::all()->random()->no_ik,
            "payor_id"      => $request->payor_id,
            "payor_cd"      => $request->payor_cd
        ];

        $data = array_merge($required, \Halim\EKlaim\Helpers\ClaimDataParser::parse($request));

        BodyBuilder::setMetadata('set_claim_data', ["nomor_sep" => $sep]);
        BodyBuilder::setData($data);

        return EklaimService::send(BodyBuilder::prepared())->then(function ($response) use ($sep, $data) {
            \Log::channel(config('eklaim.log_channel'))->info("Set klaim data success", [
                "sep"      => $sep,
                "data"     => json_decode(json_encode(BodyBuilder::prepared()), true),
                "response" => $response
            ]);

            // ==================================================== GROUPING STAGE 1 & 2
            $group = new GroupKlaimController();

            // Grouping stage 1
            $gr1 = $group->stage1(new \Halim\EKlaim\Http\Requests\GroupingStage1Request(["nomor_sep" => $sep]))->then(function ($response) use ($sep) {
                \Log::channel(config('eklaim.log_channel'))->info("Grouping stage 1 success", [
                    "sep"      => $sep,
                ]);
            });
            
            $hasilGrouping = $gr1->getData();
            if (isset($hasilGrouping->special_cmg_option)) {
                $special_cmg_option_code = array_map(function ($item) {
                    return $item->code;
                }, $hasilGrouping->special_cmg_option);

                $special_cmg_option_code = implode("#", $special_cmg_option_code);
                
                // Grouping stage 2
                $gr2 = $group->stage2(new \Halim\EKlaim\Http\Requests\GroupingStage2Request(["nomor_sep" => $sep, "special_cmg" => $special_cmg_option_code ?? '']))->then(function ($response) use ($sep, $special_cmg_option_code) {
                    \Log::channel(config('eklaim.log_channel'))->info("Grouping stage 2 success", [
                        "sep" => $sep,
                        "special_cmg" => $special_cmg_option_code ?? ''
                    ]);
                });
                
                $hasilGrouping = $gr2->getData();
            }
            // ==================================================== END OF GROUPING STAGE 1 & 2

            // ==================================================== SAVE DATAS
            $this->saveDiagnosaAndProcedures($data);
            $this->saveChunksData($data);
            // ==================================================== END OF SAVE DATAS

            try {
                $groupingData = [
                    "no_sep"    => $sep,
                    "code_cbg"  => $hasilGrouping->response->cbg->code,
                    "deskripsi" => $hasilGrouping->response->cbg->description,
                    "tarif"     => $hasilGrouping->response->cbg->tariff
                ];

                \Illuminate\Support\Facades\DB::transaction(function () use ($sep,  $groupingData) {
                    \App\Models\InacbgGropingStage12::updateOrCreate(['no_sep' => $sep], $groupingData);
                });

                \Log::channel(config('eklaim.log_channel'))->info("Data inserted to inacbg_grouping_stage12", $groupingData);
            } catch (\Throwable $th) {
                \Log::channel(config('eklaim.log_channel'))->error("Error while inserting data to inacbg_grouping_stage12", [
                    "error" => $th->getMessage()
                ]);
            }

            return $response;
        });
    }

    private function storeInacbgKlaimBaru2($no_rawat, $nomor_sep, $patient_id, $admission_id, $hospital_admission_id)
    {
        $dataToSave = [
            'no_rawat'              => $no_rawat,
            'no_sep'                => $nomor_sep,
            'patient_id'            => $patient_id,
            'admission_id'          => $admission_id,
            'hospital_admission_id' => $hospital_admission_id,
        ];

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($dataToSave) {
                \App\Models\InacbgKlaimBaru2::updateOrCreate([
                    'no_sep' => $dataToSave['no_sep']
                ], $dataToSave);
            });

            \Log::channel(config('eklaim.log_channel'))->info("Update or create data to inacbg_klaim_baru2", [
                "data"  => $dataToSave
            ]);
        } catch (\Throwable $th) {
            \Log::channel(config('eklaim.log_channel'))->error("Error while inserting data to inacbg_klaim_baru2", [
                "error"    => $th->getMessage(),
                "data"     => $dataToSave
            ]);
        }
    }

    private function saveChunksData($klaim_data)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($klaim_data) {
            \App\Models\RsiaGroupingChunks::updateOrCreate(['no_sep' => $klaim_data['nomor_sep']], [
                'cara_masuk'      => $klaim_data['cara_masuk'],
                'usia_kehamilan'  => $klaim_data['persalinan']['usia_kehamilan'] ?? null,
                'onset_kontraksi' => $klaim_data['persalinan']['onset_kontraksi'],
            ]);
        }, 5);
    }

    private function saveDiagnosaAndProcedures($klaim_data)
    {
        $explodedDiagnosa = explode("#", $klaim_data['diagnosa']);
        $explodedProcedures = explode("#", $klaim_data['procedure']);

        $no_rawat = \App\Models\BridgingSep::where('no_sep', $klaim_data['nomor_sep'])->first()->no_rawat;

        if (!$no_rawat) {
            \Log::channel(config('eklaim.log_channel'))->error("No rawat not found", [
                "no_sep"     => $klaim_data['nomor_sep'],
                "diag"       => $explodedDiagnosa,
                "procedures" => $explodedProcedures
            ]);
            return;
        }

        // save diagnosa on transaction, first delete all diagnosa, then insert new diagnosa with priority. priority is the index + 1 of the array
        \Illuminate\Support\Facades\DB::transaction(function () use ($explodedDiagnosa, $no_rawat, $klaim_data) {
            \App\Models\DiagnosaPasien::where('no_rawat', $no_rawat)->delete();

            foreach ($explodedDiagnosa as $key => $diagnosa) {
                \App\Models\DiagnosaPasien::create([
                    "no_rawat"    => $no_rawat,
                    "kd_penyakit" => $diagnosa,
                    "status"      => $klaim_data['jenis_rawat'] == 1 ? "Ranap" : "Ralan",
                    "prioritas"   => $key + 1
                ]);
            }
        }, 5);


        // save procedures on transaction, first delete all procedures, then insert new procedures with priority. priority is the index + 1 of the array
        \Illuminate\Support\Facades\DB::transaction(function () use ($explodedProcedures, $no_rawat, $klaim_data) {
            \App\Models\ProsedurPasien::where('no_rawat', $no_rawat)->delete();

            foreach ($explodedProcedures as $key => $procedure) {
                \App\Models\ProsedurPasien::create([
                    "no_rawat"  => $no_rawat,
                    "kode"      => $procedure,
                    "status"    => $klaim_data['jenis_rawat'] == 1 ? "Ranap" : "Ralan",
                    "prioritas" => $key + 1
                ]);
            }
        }, 5);
    }
}
