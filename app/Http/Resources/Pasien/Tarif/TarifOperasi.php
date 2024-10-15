<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\Operasi;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifOperasi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request, bool $sum = true)
    {
        $fields = [
            "biayaoperator1", "biayaoperator2", "biayaoperator3",
            "biayaasisten_operator1", "biayaasisten_operator2", "biayaasisten_operator3",
            "biayadokter_anestesi", "biayaasisten_anestesi", "biayaasisten_anestesi2",
            "biayabidan", "biayabidan2", "biayabidan3",
            "biaya_omloop", "biaya_omloop2", "biaya_omloop3", "biaya_omloop4", "biaya_omloop5",
            "biaya_dokter_pjanak", "biaya_dokter_umum",
            "biayainstrumen", "biayadokter_anak", "biayaperawaat_resusitas",
            "biayaperawat_luar", "biayaalat", "biayasewaok",
            "akomodasi", "bagian_rs", "biayasarpras",
        ];

        $tarif = 0;
        $data = Operasi::where('no_rawat', $this->resource);
        
        if ($sum) {
            $data = $data->get($fields)->toArray();

            foreach ($data as $item) {
                foreach ($item as $key => $value) {
                    $tarif += $value;
                }
            }
    
            return $tarif;
        }

        $data = $data->with('detailPaket')->get([...$fields, 'kode_paket']);

        return $data;
    }
}
