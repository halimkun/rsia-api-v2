<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PenerimaUndanganWithKehadiranCollection extends ResourceCollection
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

        $buildData = $this->collection->map(function ($item) {
            $kehadiran = \App\Models\RsiaKehadiranRapat::where('no_surat', $item->no_surat)
            ->where('nik', $item->penerima)->first();

            $data = [
                'no_surat'   => $item->no_surat,
                'penerima'   => $item->penerima,
                'tipe'       => $item->tipe,
                'model'      => $item->model,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];

            // merge data with kehadiran & $item->getRelations()
            $data = array_merge($data, [
                'hadir'     => $kehadiran ? true : false,
            ]);

            $data = array_merge($data, $item->getRelations());

            return $data;
        });

        return $buildData;
    }
}
