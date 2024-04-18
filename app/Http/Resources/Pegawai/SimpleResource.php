<?php

namespace App\Http\Resources\Pegawai;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pegawai = \App\Models\Pegawai::select('nik', 'nama', 'alamat', 'jk', 'jbtn', 'departemen')
            ->where('nik', $this->resource)
            ->first();

        return $pegawai;
    }
}
