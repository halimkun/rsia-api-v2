<?php

namespace App\Http\Resources\Pasien\Tarif;

use Illuminate\Http\Resources\Json\JsonResource;

class RunningTarif extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $no_rawat = $this->resource->no_rawat;
        $data = [
            'detail_pemberian_obat' => (new TarifDetailPemberianObat($no_rawat))->toArray($request),
            'kamar_inap'            => (new TarifKamarInap($no_rawat))->toArray($request),
            'operasi'               => (new TarifOperasi($no_rawat))->toArray($request),
            'periksa_lab'           => (new TarifPeriksaLab($no_rawat))->toArray($request),
            'periksa_radiologi'     => (new TarifPeriksaRadiologi($no_rawat))->toArray($request),
            'rawat_inap_dr_pr'      => (new TarifRawatInapDrPr($no_rawat))->toArray($request),
            'rawat_inap_pr'         => (new TarifRawatInapPr($no_rawat))->toArray($request),
            'rawat_inap_dr'         => (new TarifRawatInapDr($no_rawat))->toArray($request),
            'rawat_jalan_dr_pr'     => (new TarifRawatJalanDrPr($no_rawat))->toArray($request),
            'rawat_jalan_pr'        => (new TarifRawatJalanPr($no_rawat))->toArray($request),
            'rawat_jalan_dr'        => (new TarifRawatJalanDr($no_rawat))->toArray($request),
        ];

        // $data['total'] = collect($data)->sum(function ($item) {
        //     return $item;
        // });

        return $data;
    }
}
