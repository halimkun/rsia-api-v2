<?php

namespace App\Http\Resources\Pegawai;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PegawaiCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $select = $request->input('select', '*');
        $data = $this->collection->transform(function ($pegawai) use ($select) {
            $pegawai = $pegawai->only(explode(',', $select));
            return $pegawai;
        });

        return $data;
    }
}
