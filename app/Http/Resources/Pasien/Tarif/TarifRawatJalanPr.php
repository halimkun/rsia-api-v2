<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\RawatJalanPr;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifRawatJalanPr extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tarif = RawatJalanPr::where('no_rawat', $this->resource)->sum('biaya_rawat');
        return $tarif;
    }
}
