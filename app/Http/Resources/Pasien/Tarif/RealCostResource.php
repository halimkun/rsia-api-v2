<?php

namespace App\Http\Resources\Pasien\Tarif;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class RealCostResource extends JsonResource
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
        $gabung = \App\Models\RanapGabung::select('no_rawat2')->where('no_rawat', $no_rawat)->first();

        $data['tarif_ibu'] = $this->getTarif($no_rawat, $request);

        if ($gabung) {
            $data['tarif_anak'] = $this->getTarif($gabung->no_rawat2, $request);

            $data['tarif_ibu_anak'] = collect($data['tarif_ibu'])->mergeRecursive($data['tarif_anak'])
                ->mapWithKeys(function ($item, $key) {
                    return [$key => collect($item)->flatten()->sum()];
                });
        }

        // Sum all individual tarif elements
        $data['total_tarif'] = isset($data['tarif_ibu_anak']) ? collect($data['tarif_ibu_anak'])->sum() : collect($data['tarif_ibu'])->sum();

        return $data;
    }

    private function getTarif($no_rawat, \Illuminate\Http\Request $request)
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
