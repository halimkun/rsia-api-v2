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

        // Ambil data kamar inap
        $dataPulang = $this->collection->where('stts_pulang', '<>', 'Pindah Kamar')->first();
        
        $masuk  = Carbon::parse($regPeriksa->tgl_registrasi); //  . " " . $regPeriksa->jam_reg
        if ($dataPulang->tgl_keluar == "0000-00-00" || $dataPulang->tgl_keluar == '00:00:00') {
            $keluar = Carbon::now();
        } else {
            $keluar = Carbon::parse($dataPulang->tgl_keluar); // . " " . $dataPulang->jam_keluar
        }

        // ========== HITUNG JUMALAH HARI
        $days = $masuk->diffInDays($keluar) + 1;
        
        // ========== HITUNG SELISIH WAKTU DALAM MENIT
        // tambahkan jam_reg ke massuk dan jam keluar ke keluar
        $masuk->setTimeFromTimeString($regPeriksa->jam_reg);
        $keluar->setTimeFromTimeString($this->collection->first()->jam_keluar);

        // Hitung selisih waktu dalam menit
        $diffMinutes = $masuk->diffInMinutes($keluar);

        // ========== FORMAT DURASI
        $durasi = sprintf("%02d:%02d", floor($diffMinutes / 60), $diffMinutes % 60);

        // map the collection to get the data
        return [
            "lama_inap" => $days,
            "lama_jam"  => $durasi,
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

    private function getDiffMinutesSecond(Carbon $masuk, Carbon $keluar)
    {
        $diffHour = $masuk->diffInHours($keluar);
        $diffMinute = $masuk->diffInMinutes($keluar) % 60;

        return str_pad($diffHour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($diffMinute, 2, "0", STR_PAD_LEFT);
    }
}
