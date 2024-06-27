<?php

namespace App\Http\Resources\Kamar\Inap;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShowKamarInapCollection extends ResourceCollection
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

        $regPeriksa = \App\Models\RegPeriksa::where('no_rawat', $this->collection->first()->no_rawat)->first();

        // map the collection to get the data
        return [
            "lama_inap" => $this->collection->sum("lama"),
            "lama_jam" => $this->getDiffMinutesSecond(
                Carbon::parse($regPeriksa->tgl_registrasi . " " . $regPeriksa->jam_reg),
                Carbon::parse($this->collection->first()->tgl_keluar . " " . $this->collection->first()->jam_keluar)
            ),
            "pasien_bayi"    => $regPeriksa->pasienBayi,
            "detail" => $this->collection->map(function ($kamarInap) {
                return [
                    "no_rawat"       => $kamarInap->no_rawat,
                    "kd_kamar"       => $kamarInap->kd_kamar,
                    "trf_kamar"      => $kamarInap->trf_kamar,
                    "diagnosa_awal"  => $kamarInap->diagnosa_awal,
                    "diagnosa_akhir" => $kamarInap->diagnosa_akhir,
                    "tgl_masuk"      => $kamarInap->tgl_masuk,
                    "jam_masuk"      => $kamarInap->jam_masuk,
                    "tgl_keluar"     => $kamarInap->tgl_keluar,
                    "jam_keluar"     => $kamarInap->jam_keluar,
                    "lama"           => $kamarInap->lama,
                    "ttl_biaya"      => $kamarInap->ttl_biaya,
                    "stts_pulang"    => $kamarInap->stts_pulang,
                ];
            })
        ];
    }

    private function getDiffMinutesSecond(Carbon $masuk, Carbon $keluar) {
        $diffHour = $masuk->diffInHours($keluar);
        $diffMinute = $masuk->diffInMinutes($keluar) % 60;

        return str_pad($diffHour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($diffMinute, 2, "0", STR_PAD_LEFT);
    }
}
