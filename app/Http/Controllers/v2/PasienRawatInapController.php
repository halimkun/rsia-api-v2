<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasienRawatInapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = \App\Models\KamarInap::where('tgl_keluar', '0000-00-00')
            ->where('stts_pulang', '-')
            ->with(['regPeriksa' => function ($q) {
                $q->select("no_rawat", "no_rkm_medis", "tgl_registrasi", "jam_reg", "p_jawab", "hubunganpj")
                    ->with(['pasien' => function ($q) {
                        $q->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir');
                    }]);
            }])
            ->paginate(10);

        return new \App\Http\Resources\Pasien\Ranap\CompleteCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    public function tarif(Request $request)
    {
        $data = \App\Models\RegPeriksa::select("no_rawat");
        if ($request->has('filters')) {
            foreach ($request->filters as $filter) {
                if ($filter['operator'] == 'in') {
                    $data->whereIn($filter['field'], $filter['value']);
                    continue;
                }

                $data->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        $data = $data->get()->pluck('no_rawat');

        $runningTarif = [];
        foreach ($data as $no_rawat) {
            // check gabung
            $gabung = \App\Models\RanapGabung::select('no_rawat2')->where('no_rawat', $no_rawat)->first();

            if ($gabung) {
                $runningTarif[$no_rawat] = $this->getTarif($no_rawat, $request);
                $runningTarif[$no_rawat]['gabung'] = $this->getTarif($gabung->no_rawat2, $request);
            } else {
                $runningTarif[$no_rawat] = $this->getTarif($no_rawat, $request);
            }

            // jumlah total without gabung
            $runningTarif[$no_rawat]['total'] = collect($runningTarif[$no_rawat])->sum(function ($item) {
                return $item ? collect($item)->sum() : 0;
            });
        }

        sleep(1.2);

        return new \App\Http\Resources\Pasien\Tarif\DefaultTarifResource(collect($runningTarif));
    }

    private function getTarif($no_rawat, Request $request)
    {
        return [
            'detail_pemberian_obat' => (new \App\Http\Resources\Pasien\Tarif\TarifDetailPemberianObat($no_rawat))->toArray($request),
            'kamar_inap'            => (new \App\Http\Resources\Pasien\Tarif\TarifKamarInap($no_rawat))->toArray($request),
            'operasi'               => (new \App\Http\Resources\Pasien\Tarif\TarifOperasi($no_rawat))->toArray($request),
            'periksa_lab'           => (new \App\Http\Resources\Pasien\Tarif\TarifPeriksaLab($no_rawat))->toArray($request),
            'periksa_radiologi'     => (new \App\Http\Resources\Pasien\Tarif\TarifPeriksaRadiologi($no_rawat))->toArray($request),
            'rawat_inap_dr_pr'      => (new \App\Http\Resources\Pasien\Tarif\TarifRawatInapDrPr($no_rawat))->toArray($request),
            'rawat_inap_pr'         => (new \App\Http\Resources\Pasien\Tarif\TarifRawatInapPr($no_rawat))->toArray($request),
            'rawat_inap_dr'         => (new \App\Http\Resources\Pasien\Tarif\TarifRawatInapDr($no_rawat))->toArray($request),
            'rawat_jalan_dr_pr'     => (new \App\Http\Resources\Pasien\Tarif\TarifRawatJalanDrPr($no_rawat))->toArray($request),
            'rawat_jalan_pr'        => (new \App\Http\Resources\Pasien\Tarif\TarifRawatJalanPr($no_rawat))->toArray($request),
            'rawat_jalan_dr'        => (new \App\Http\Resources\Pasien\Tarif\TarifRawatJalanDr($no_rawat))->toArray($request),
        ];
    }
}
