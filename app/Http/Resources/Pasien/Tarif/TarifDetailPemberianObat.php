<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\DetailPemberianObat;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifDetailPemberianObat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = DetailPemberianObat::where('no_rawat', $this->resource)->sum('total');
        return $data;
    }
}
