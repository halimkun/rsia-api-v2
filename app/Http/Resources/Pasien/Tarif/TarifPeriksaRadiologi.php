<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\PeriksaRadiologi;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifPeriksaRadiologi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tarif = PeriksaRadiologi::where('no_rawat', $this->resource)->sum('biaya');
        return $tarif;
    }
}
