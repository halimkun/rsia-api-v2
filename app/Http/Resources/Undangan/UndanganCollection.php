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

        return $this->collection->map(function ($item) use ($request) {
            // Membuat instance model dari item
            $model = new $item->model;

            // Melakukan pencarian menggunakan kata kunci pada model
            $undangan = $model->where('no_surat', $item->no_surat)->with('penanggungJawabSimple')->first();

            // Mengambil jumlah penerima dan tipe undangan berdasarkan no_surat
            $penerimaCount = RsiaPenerimaUndangan::where('no_surat', $item->no_surat)->count();
            $undanganTipe = RsiaPenerimaUndangan::where('no_surat', $item->no_surat)->first();

            // Verifikasi apakah $undangan adalah array atau objek
            if (is_array($undangan)) {
                $undangan = (object) $undangan;
            }

            return [
                'no_surat'       => $item->no_surat,
                'tipe'           => $undanganTipe ? $undanganTipe->tipe : null,
                'penerima_count' => $penerimaCount,
                'undangan'       => $undangan,
            ];
        });
    }
}
