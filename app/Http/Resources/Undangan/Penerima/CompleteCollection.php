<?php

namespace App\Http\Resources\Undangan\Penerima;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CompleteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $model = $this->collection->first()->model;
        $model = new $model;

        $undangan = $model->where('no_surat', $this->collection->first()->no_surat)->first();

        return [
            'undangan' => $undangan,
            'penerima' => $this->collection->map(function ($item) {
                $item['nama'] = \App\Models\Pegawai::where('nik', $item['penerima'])->first()->nama;
                $item['nik'] = $item['penerima'];
                unset($item['penerima']);
                
                return collect($item)->except(['model', 'tipe']);
            }),
        ];
    }
}
