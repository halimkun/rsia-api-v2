<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealCostController extends Controller
{
    /**
     * Real cost untuk pasien rawat inap
     * 
     * endpoint ini menampilkan semua real cost dari pasien rawat inap dengan parameter no_rawat, endpoint ini menggunakan metode POST.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Pasien\Tarif\DefaultTarifResource
     * */
    public function ranap(Request $request)
    {
        if (!$request->has('filters')) {
            return ApiResponse::error('Failed to get data', 'invalid_request', null, 400);
        }

        $filters = $request->filters;
  
        if (!is_array($filters)) {
            return ApiResponse::error('Failed to get data', 'invalid_request', null, 400);
        }

        if (empty($filters)) {
            return ApiResponse::error('Failed to get data', 'invalid_request', null, 400);
        }

        foreach ($filters as $filter) {
            if (!isset($filter['field'], $filter['operator'], $filter['value']) && $filter['operator'] != 'in') {
                return ApiResponse::error('Failed to get data : Filters must have field, operator, and value', 'invalid_request', null, 400);
            }
        }

        $data = \App\Models\RegPeriksa::select("no_rawat");

        foreach ($filters as $filter) {
            if ($filter['operator'] == 'in') {
                $data->whereIn($filter['field'], $filter['value']);
            } else {
                $data->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        $data = $data->get()->pluck('no_rawat');
        $runningTarif = [];

        foreach ($data as $no_rawat) {
            $gabung = \App\Models\RanapGabung::select('no_rawat2')->where('no_rawat', $no_rawat)->first();

            $tarif = $this->getTarif($no_rawat, $request);
            if ($gabung) {
                $tarif['gabung'] = $this->getTarif($gabung->no_rawat2, $request);
            }

            $tarif['total'] = collect($tarif)->sum(fn ($item) => $item ? collect($item)->sum() : 0);
            $runningTarif[$no_rawat] = $tarif;
        }

        sleep(1.2);

        return new \App\Http\Resources\Pasien\Tarif\DefaultTarifResource(collect($runningTarif));
    }

    public function getTarif($no_rawat, Request $request)
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
            // tambahan_biaya
        ];
    }
}
