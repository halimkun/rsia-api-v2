<?php

namespace App\Http\Resources\Pasien\Ranap;

use App\Models\KamarInap;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PasienRanapCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $data = $this->collection->map(function ($item) {

            // sum lama from kamar inap where item->no_rawat
            $lamaInap = KamarInap::where('no_rawat', $item->no_rawat)->sum('lama');
            $berkasPerawatan = \App\Models\BerkasDigitalPerawatan::where('no_rawat', $item->no_rawat)->where('kode', '009')->first();

            return [
                // data kamar inap
                "no_rawat" => $item->no_rawat,
                "kd_kamar" => $item->kd_kamar,
                "trf_kamar" => $item->trf_kamar,
                "diagnosa_awal" => $item->diagnosa_awal,
                "diagnosa_akhir" => $item->diagnosa_akhir,
                "tgl_masuk" => $item->tgl_masuk,
                "jam_masuk" => $item->jam_masuk,
                "tgl_keluar" => $item->tgl_keluar,
                "jam_keluar" => $item->jam_keluar,
                "ttl_biaya" => $item->ttl_biaya,
                "stts_pulang" => $item->stts_pulang,

                // lama inap
                "sum_lama" => $lamaInap,

                // pasien data
                "pasien" => $item->pasien ? [
                    'nm_pasien' => $item->pasien->nm_pasien,
                    'no_rkm_medis' => $item->pasien->no_rkm_medis,
                ] : null,

                // data reg_periksa
                "reg_periksa" => $item->regPeriksa ? [
                    "no_rawat" => $item->regPeriksa->no_rawat,
                    "tgl_registrasi" => $item->regPeriksa->tgl_registrasi,
                    "jam_reg" => $item->regPeriksa->jam_reg,
                    "no_rkm_medis" => $item->regPeriksa->no_rkm_medis,
                    "status_lanjut" => $item->regPeriksa->status_lanjut
                ] : null,

                "berkas_perawatan" => $berkasPerawatan,

                // data sep
                "sep" => $item->sep ? [
                    'no_sep' => $item->sep->no_sep,
                    'diagawal' => $item->sep->diagawal,
                    'klsrawat' => $item->sep->klsrawat,
                    'klsnaik' => $item->sep->klsnaik,
                    'status_klaim' => $item->sep->status_klaim,
                ] : null,
            ];
        });

        return $data;
    }
}
