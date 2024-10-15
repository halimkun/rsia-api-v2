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
    public function toArray($request, bool $sum = true)
    {
        $data = DetailPemberianObat::where('no_rawat', $this->resource);
        if ($sum) {
            $data = $data->sum('total');
        } else {
            $data = $data->get();
        }
        
        return $data;
    }
}
