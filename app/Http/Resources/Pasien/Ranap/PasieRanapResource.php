<?php

namespace App\Http\Resources\Pasien\Ranap;

use Illuminate\Http\Resources\Json\JsonResource;

class PasieRanapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
