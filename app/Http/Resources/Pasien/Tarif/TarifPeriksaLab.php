<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\PeriksaLab;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifPeriksaLab extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tarif = PeriksaLab::where('no_rawat', $this->resource)->sum('biaya');
        return $tarif;
    }
}
