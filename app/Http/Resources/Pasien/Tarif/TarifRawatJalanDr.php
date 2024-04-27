<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\RawatJalanDr;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifRawatJalanDr extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tarif = RawatJalanDr::where('no_rawat', $this->resource)->sum('biaya_rawat');
        return $tarif;
    }
}
