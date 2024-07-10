<?php

namespace App\Http\Resources\Booking\Registrasi;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingRegistrasiCollection extends ResourceCollection
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
        $tglRegistrasi = $this->collection->isEmpty() ? \Carbon\Carbon::now()->format('Y-m-d') : $this->collection->first()->tanggal_periksa;

        $regPeriksaSumData = \App\Models\RegPeriksa::where('tgl_registrasi', $tglRegistrasi)
            ->where('stts', 'Belum')
            ->groupBy('kd_poli', 'kd_dokter')
            ->selectRaw('kd_poli, kd_dokter, count(*) as total')
            ->get();

        return $this->collection->map(function ($item) use ($regPeriksaSumData) {
            $bookingData = [
                "tanggal_booking" => $item->tanggal_booking,
                "jam_booking"     => $item->jam_booking,
                "no_rkm_medis"    => $item->no_rkm_medis,
                "tanggal_periksa" => $item->tanggal_periksa,
                "kd_dokter"       => $item->kd_dokter,
                "kd_poli"         => $item->kd_poli,
                "no_reg"          => $item->no_reg,
                "kd_pj"           => $item->kd_pj,
                "limit_reg"       => $item->limit_reg,
                "waktu_kunjungan" => $item->waktu_kunjungan,
                "status"          => $item->status,
            ];
            
            $data = array_merge($bookingData, $item->getRelations());

            $data['jadwal'] = \App\Models\JadwalPoli::select('jam_mulai', 'jam_selesai', 'hari_kerja')
                ->where('kd_poli', $item->kd_poli)
                ->where('kd_dokter', $item->kd_dokter)
                ->where('hari_kerja', strtoupper(\Carbon\Carbon::parse($item->tanggal_periksa)->translatedFormat('l')))
                ->first();

            // merge with regPeriksaSumData
            $data = array_merge($data, [
                "total_booking" => $regPeriksaSumData->where('kd_poli', $item->kd_poli)
                    ->where('kd_dokter', $item->kd_dokter)
                    ->first()
            ]);

            return $data;
        });
    }
}
