<?php

namespace App\Http\Resources\Undangan\Penerima;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RealCollection extends ResourceCollection
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

        return $this->collection->map(function ($item) {
            $pegawai = \App\Models\Pegawai::where('nik', $item['penerima'])->first();
            $dep = \App\Models\Departemen::where('dep_id', $pegawai->departemen)->first();

            $item['nama'] = $pegawai->nama;
            $item['nik'] = $item['penerima'];
            $item['bidang'] = $pegawai->bidang;
            $item['jk'] = $pegawai->jk;
            $item['departemen'] = $pegawai->departemen;
            $item['dep'] = $dep;

            unset($item['penerima']);

            return collect($item)->except(['model', 'tipe']);
        });
    }
}
