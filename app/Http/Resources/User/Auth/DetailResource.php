<?php

namespace App\Http\Resources\User\Auth;

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
            "id_user" => $this->id_user,
            "detail" => \App\Http\Resources\Pegawai\SimpleResource::make($this->id_user),
        ];
    }
}
