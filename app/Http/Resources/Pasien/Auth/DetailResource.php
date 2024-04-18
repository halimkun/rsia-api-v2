<?php

namespace App\Http\Resources\Pasien\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'no_rkm_medis' => $this->no_rkm_medis,
            'tgl_daftar' => $this->tgl_daftar,

            'nm_pasien' => $this->nm_pasien,
            'jenis_kelamin' => $this->jk,
            'tgl_lahir' => $this->tgl_lahir,
            
            'alamat' => $this->alamat,
            'no_tlp' => $this->no_tlp,
            'email' => $this->email,
        ];
    }
}
