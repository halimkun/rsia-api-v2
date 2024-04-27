<?php

namespace App\Http\Resources\Pasien\Tarif;

use App\Models\RawatJalanDrPr;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifRawatJalanDrPr extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tarif = RawatJalanDrPr::where('no_rawat', $this->resource)->sum('biaya_rawat');
        return $tarif;
    }
}
