<?php

namespace App\Http\Resources\Pegawai;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PegawaiCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $select = $request->input('select', '*');
        $data = $this->collection->transform(function ($item) use ($select, $request) {

            if ($select == "*") {
                $modifieditem = $item;
            } else {
                $modifieditem = $item->only(explode(',', $select));
            }

            // add dep to item
            if ($request->has('include') && in_array('dep', explode(',', $request->input('include')))) {
                $modifieditem['dep'] = $item->dep;
            }

            return $modifieditem;
        });

        return $data;
    }
}
