<?php

namespace App\Http\Resources\Undangan;

use App\Models\RsiaPenerimaUndangan;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UndanganCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        
        return $this->collection->map(function ($item) {
            $model = new $item->model;
            $penerimaCount = RsiaPenerimaUndangan::where('no_surat', $item->no_surat)->count();
            
            return [
                'no_surat' => $item->no_surat,
                'penerima_count' => $penerimaCount,
                'undangan' => $model->where('no_surat', $item->no_surat)->first(),
            ];
        });
    }
}
