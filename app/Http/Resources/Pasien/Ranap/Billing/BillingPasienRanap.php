<?php

namespace App\Http\Resources\Pasien\Ranap\Billing;

use App\Models\Billing;
use App\Models\RegPeriksa;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingPasienRanap extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $pasien = RegPeriksa::select('no_rkm_medis', 'no_rawat')->with('pasienSomeData')->where('no_rawat', $this->resource)->first();        
        // $pasien->billing = $this->getBillingData($this->resource);

        return $this->getBillingData($this->resource);
    }

    private function getBillingData($noRawat)
    {
        $kamarBilling    = Billing::where('no_rawat', $noRawat)->where('status', 'Kamar')->sum('totalbiaya');
        $kamarRegPeriksa = RegPeriksa::where('no_rawat', $noRawat)->value('biaya_reg');
        $kamar           = $kamarBilling + $kamarRegPeriksa;
        $obatKronis      = Billing::where('no_rawat', $noRawat)->where('status', 'Obat')->where('nm_perawatan', 'like', '%kronis%')->sum('totalbiaya');
        $obatKemoterapi  = Billing::where('no_rawat', $noRawat)->where('status', 'Obat')->where('nm_perawatan', 'like', '%kemo%')->sum('totalbiaya');
        $obat            = Billing::where('no_rawat', $noRawat)->whereIn('status', ['Obat', 'Retur Obat', 'Resep Pulang'])->sum('totalbiaya') - $obatKronis - $obatKemoterapi;

        return [
            'prosedur_non_bedah' => Billing::where('no_rawat', $noRawat)->whereIn('status', ['Ralan Dokter Paramedis', 'Ranap Dokter Paramedis'])->where('nm_perawatan', 'not like', '%terapi%')->sum('totalbiaya') ?: 0,
            'prosedur_bedah'     => Billing::where('no_rawat', $noRawat)->where('status', 'Operasi')->sum('totalbiaya') ?: 0,
            'konsultasi'         => Billing::where('no_rawat', $noRawat)->whereIn('status', ['Ranap Dokter', 'Ralan Dokter'])->sum('totalbiaya') ?: 0,
            'tenaga_ahli'        => 0,
            'keperawatan'        => Billing::where('no_rawat', $noRawat)->whereIn('status', ['Ranap Paramedis', 'Ralan Paramedis'])->sum('totalbiaya') ?: 0,
            'radiologi'          => Billing::where('no_rawat', $noRawat)->where('status', 'Radiologi')->sum('totalbiaya') ?: 0,
            'laboratorium'       => Billing::where('no_rawat', $noRawat)->where('status', 'Laborat')->sum('totalbiaya') ?: 0,
            'kamar'              => $kamar ?: 0,
            'obat'               => $obat ?: 0,
            'obat_kronis'        => $obatKronis ?: 0,
            'obat_kemoterapi'    => $obatKemoterapi ?: 0,
            'bmhp'               => Billing::where('no_rawat', $noRawat)->where('status', 'Tambahan')->sum('totalbiaya') ?: 0,
            'sewa_alat'          => Billing::where('no_rawat', $noRawat)->whereIn('status', ['Harian', 'Service'])->sum('totalbiaya') ?: 0,
            'rehabilitasi'       => Billing::where('no_rawat', $noRawat)->whereIn('status', ['Ralan Dokter Paramedis', 'Ranap Dokter Paramedis'])->where('nm_perawatan', 'like', '%terapi%')->sum('totalbiaya') ?: 0,
        ];
    }
}
