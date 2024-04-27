<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\KamarInap;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifKamarInap extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // lama inap sum of ttl_biaya
        $tarif = KamarInap::where('no_rawat', $this->resource)->sum('ttl_biaya');
        return $tarif;
    }
}
