<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
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

        return $response_data;
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

        $required = [
            "nomor_sep"     => $sep,
            // "coder_nik"     => $request->coder_nik ?? \App\Models\RsiaCoderNik::all()->random()->no_ik,
            "coder_nik"     => "3326105603750002",
            "payor_id"      => $request->payor_id,
            "payor_cd"      => $request->payor_cd
        ];

        \Log::info("Set klaim data", [
            "sep"  => $sep,
            "data" => \Halim\EKlaim\Helpers\ClaimDataParser::parse($request)
        ]);

        $data = array_merge($required, \Halim\EKlaim\Helpers\ClaimDataParser::parse($request));

        // [0]. Re-Edit Klaim
        BodyBuilder::setMetadata('reedit_claim');
        BodyBuilder::setData(["nomor_sep" => $sep]);
        
        EklaimService::send(BodyBuilder::prepared())->then(function ($response) use ($sep) {
            \Log::channel(config('eklaim.log_channel'))->info("Re-Edit klaim success", [
                "sep"      => $sep,
                "response" => $response
            ]);
        });

        
        // [1]. Set claim data
        usleep(rand(500, 2000) * 1000);
        
        BodyBuilder::setMetadata('set_claim_data', ["nomor_sep" => $sep]);
        BodyBuilder::setData($data);

        EklaimService::send(BodyBuilder::prepared())->then(function ($response) use ($sep, $data) {
            \Log::channel(config('eklaim.log_channel'))->info("Set klaim data success", [
                "sep"      => $sep,
                "data"     => json_decode(json_encode(BodyBuilder::prepared()), true),
                "response" => $response
            ]);

            // ==================================================== SAVE DATAS

            $this->saveDiagnosaAndProcedures($data);
            $this->saveChunksData($data);

            // ==================================================== END OF SAVE DATAS
        });

        
        // [2]. Grouping stage 1 & 2
        usleep(rand(500, 2000) * 1000);

        $hasilGrouping = $this->groupStages($sep);
        $responseCode = $hasilGrouping->response->cbg ? $hasilGrouping->response->cbg->code : null;

        // cekNaikKelas
        $this->cekNaikKelas($sep, $hasilGrouping);

        // [3]. Final Klaim
        usleep(rand(500, 2000) * 1000);

        BodyBuilder::setMetadata('claim_final');
        BodyBuilder::setData([
            "nomor_sep" => $sep,
            "coder_nik" => '3326105603750002',
        ]);

        EklaimService::send(BodyBuilder::prepared())->then(function ($response) use ($sep) {
            \Log::channel(config('eklaim.log_channel'))->info("Final klaim success", [
                "sep"      => $sep,
                "response" => $response
            ]);
        });


        \Log::channel(config('eklaim.log_channel'))->info("HASIL", [
            "HASIL" => $hasilGrouping,
            "RESPONSE CODE" => $responseCode
        ]);

        if ($responseCode && (\Illuminate\Support\Str::startsWith($responseCode, 'X') || \Illuminate\Support\Str::startsWith($responseCode, 'x'))) {
            return ApiResponse::error($hasilGrouping->response->cbg->code . " : " . $hasilGrouping->response->cbg->description, 500);
        }

        return ApiResponse::successWithData($hasilGrouping->response, "Grouping Berhasil dilakukan");
    }

    /**
     * get klaim data method
     *
     * method to get klaim data from eklaim service and save it to our database
     *  
     * @param string $sep
     * 
     * @return \Illuminate\Http\JsonResponse
     * */
    public function sync($sep)
    {
        BodyBuilder::setMetadata('get_claim_data');
        BodyBuilder::setData(["nomor_sep" => $sep]);

        $response = EklaimService::send(BodyBuilder::prepared());

        // Pastikan response berhasil (status code 200)
        if ($response->getStatusCode() !== 200) {
            return $this->logAndReturnError("Error while getting klaim data", $response);
        }

        $responseData = $response->getData()?->response?->data ?? null;
        if (!$responseData) {
            return $this->logAndReturnError("Error while getting klaim data", $response);
        }

        $cbg = $responseData->grouper?->response?->cbg ?? null;
        if (!$cbg) {
            return $this->logAndReturnError("Error while getting klaim data", $response);
        }

        $groupingData = [
            'no_sep'    => $sep,
            'code_cbg'  => $cbg->code ?? null,
            'deskripsi' => $cbg->description ?? null,
            'tarif'     => $cbg->tariff ?? null,
        ];

        // Melakukan transaksi penyimpanan data ke database
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($sep, $groupingData) {
                \App\Models\InacbgGropingStage12::where('no_sep', $sep)->delete();
                \App\Models\InacbgGropingStage12::create($groupingData);
            }, 5);

            \Log::channel(config('eklaim.log_channel'))->info("Data inserted to inacbg_grouping_stage12", $groupingData);
            return ApiResponse::successWithData($responseData, "Grouping Berhasil dilakukan");
        } catch (\Throwable $th) {
            \Log::channel(config('eklaim.log_channel'))->error("Error while inserting data to inacbg_grouping_stage12", [
                "error" => $th->getMessage()
            ]);
            return ApiResponse::error("Error while inserting data to inacbg_grouping_stage12", 500);
        }
    }

    /**
     * Helper method untuk log dan kembalikan error.
     */
    protected function logAndReturnError($message, $response)
    {
        \Log::channel(config('eklaim.log_channel'))->error($message, json_decode(json_encode($response->getData()), true));
        return ApiResponse::error($message, 500);
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
                'cara_masuk'      => $klaim_data['cara_masuk'] ?? null,
                'sistole'         => $klaim_data['sistole'] ?? null,
                'diastole'        => $klaim_data['diastole'] ?? null,
                'usia_kehamilan'  => $klaim_data['persalinan']['usia_kehamilan'] ?? null,
                'onset_kontraksi' => $klaim_data['persalinan']['onset_kontraksi'] ?? null,
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
                if (empty($diagnosa)) {
                    continue;
                }

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
                if (empty($procedure)) {
                    continue;
                }

                \App\Models\ProsedurPasien::create([
                    "no_rawat"  => $no_rawat,
                    "kode"      => $procedure,
                    "status"    => $klaim_data['jenis_rawat'] == 1 ? "Ranap" : "Ralan",
                    "prioritas" => $key + 1
                ]);
            }
        }, 5);
    }

    private function groupStages($sep)
    {
        // ==================================================== GROUPING STAGE 1 & 2
        $group = new GroupKlaimController();

        // Grouping stage 1
        $gr2 = null;
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

        // log stage 1 and 2
        \Log::channel(config('eklaim.log_channel'))->info("Grouping stage 1 & 2 data", [
            "sep" => $sep,
            "stage1" => $gr1->getData(),
            "stage2" => $gr2?->getData()
        ]);

        // ==================================================== END OF GROUPING STAGE 1 & 2

        try {
            $groupingData = [
                "no_sep"    => $sep,
                "code_cbg"  => $hasilGrouping->response->cbg->code,
                "deskripsi" => $hasilGrouping->response->cbg->description,
                "tarif"     => $hasilGrouping->response->cbg ? $hasilGrouping->response->cbg->tariff : null,
            ];

            \Illuminate\Support\Facades\DB::transaction(function () use ($sep,  $groupingData) {
                \App\Models\InacbgGropingStage12::where('no_sep', $sep)->delete();
                \App\Models\InacbgGropingStage12::create($groupingData);
            }, 5);

            \Log::channel(config('eklaim.log_channel'))->info("Data inserted to inacbg_grouping_stage12", $groupingData);
        } catch (\Throwable $th) {
            \Log::channel(config('eklaim.log_channel'))->error("Error while inserting data to inacbg_grouping_stage12", [
                "error" => $th->getMessage()
            ]);
        }

        return $hasilGrouping;
    }

    private function cekNaikKelas($sep, $groupResponse)
    {
        $sep = \App\Models\BridgingSep::where('no_sep', $sep)->first();

        // Pastikan sep valid dan kelas naik tidak lebih dari 3
        if (!$sep || $sep->klsnaik > 3) return;

        // Periksa spesialis dokter, lanjutkan hanya jika spesialisnya kandungan
        $regPeriksa = \App\Models\RegPeriksa::with('dokter.spesialis')->where('no_rawat', $sep->no_rawat)->first();
        if (!\Str::contains(\Str::lower($regPeriksa->dokter->spesialis->nm_sps), 'kandungan')) return;

        // Periksa kelas VIP A atau VIP B
        $kamarInap = \App\Models\KamarInap::where('no_rawat', $sep->no_rawat)->latest('tgl_masuk')->latest('jam_masuk')->first();
        if (!\Str::contains(\Str::lower($kamarInap->kd_kamar), ['kandungan va', 'kandungan vb1', 'kandungan vb2'])) return;

        // Ambil tarif dan tarif alternatif kelas 1
        $cbgTarif = $groupResponse?->response?->cbg?->tariff ?? 0;
        $altTariKelas1 = collect($groupResponse->tarif_alt)->where('kelas', 'kelas_1')->first()?->tarif_inacbg ?? 0;

        if (!$altTariKelas1) {
            return ApiResponse::error("Pasien Naik Kelas namun, alt tarif kelas tidak ditemukan", 500);
        }

        // Tentukan presentase dan hitung tarif tambahan berdasarkan kelas kamar inap
        $presentase = \Str::contains(\Str::lower($kamarInap->kd_kamar), 'kandungan va') ? 73 : 43;
        $tambahanBiaya = $altTariKelas1 - $cbgTarif + ($altTariKelas1 * $presentase / 100);

        // Simpan data naik kelas
        \App\Models\RsiaNaikKelas::updateOrCreate(
            ['no_sep' => $sep->no_sep], // Kondisi untuk update
            [
                'jenis_naik'  => "Naik " . \App\Helpers\NaikKelasHelper::getJumlahNaik($sep->klsrawat, $sep->klsnaik) . " Kelas",
                'tarif_1'     => $altTariKelas1,
                'tarif_2'     => $cbgTarif,
                'presentase'  => $presentase,
                'tarif_akhir' => $tambahanBiaya,
                'diagnosa'    => $sep->nmdiagnosaawal
            ]
        );
    }
}
