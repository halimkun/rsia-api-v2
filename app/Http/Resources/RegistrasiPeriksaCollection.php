<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RegistrasiPeriksaCollection extends ResourceCollection
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

        $tglRegistrasi = $this->collection->isEmpty() ? \Carbon\Carbon::now()->format('Y-m-d') : $this->collection->first()->tgl_registrasi;

        $regPeriksaSumData = \App\Models\RegPeriksa::where('tgl_registrasi', $tglRegistrasi)
            ->where('stts', 'Belum')
            ->groupBy('kd_poli', 'kd_dokter')
            ->selectRaw('kd_poli, kd_dokter, count(*) as total')
            ->get();

        return $this->collection->map(function ($item) use ($regPeriksaSumData) {
            $registrasiData = [
                "no_reg"         => $item->no_reg,
                "no_rawat"       => $item->no_rawat,
                "tgl_registrasi" => $item->tgl_registrasi,
                "jam_reg"        => $item->jam_reg,
                "kd_dokter"      => $item->kd_dokter,
                "no_rkm_medis"   => $item->no_rkm_medis,
                "kd_poli"        => $item->kd_poli,
                "stts"           => $item->stts,
                "stts_daftar"    => $item->stts_daftar,
                "status_lanjut"  => $item->status_lanjut,
                "kd_pj"          => $item->kd_pj,
                "status_poli"    => $item->status_poli
            ];
                
            $data = array_merge($registrasiData, $item->getRelations());

            $data['jadwal'] = \App\Models\JadwalPoli::select('jam_mulai', 'jam_selesai', 'hari_kerja')
                ->where('kd_poli', $item->kd_poli)
                ->where('kd_dokter', $item->kd_dokter)
                ->where('hari_kerja', strtoupper(\Carbon\Carbon::parse($item->tgl_registrasi)->translatedFormat('l')))
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
