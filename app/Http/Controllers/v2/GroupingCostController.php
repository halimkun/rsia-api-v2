<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupingCostController extends Controller
{
    /**
     * Grouping cost untuk pasien rawat inap
     * 
     * endpoint ini akan mengembalikan data grouping cost dari pasien rawat inap yang sudah digroping INA-CBGs, parameter no_sep diperlukan untuk bisa mendapatkan data yang dimaksud. 
     * untuk lebih detail mengenai groping, INA-CBGs dan WebService BPJS bisa melihat api catalog berikut https://inacbg.kemkes.go.id/DL/Manual%20Web%20Service%205.8.3b.pdf
     * 
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Pasien\Tarif\DefaultTarifResource
     * */ 
    public function ranap(Request $request)
    {
        $data = \App\Models\InacbgGropingStage12::select("*");
        if ($request->has('filters')) {
            foreach ($request->filters as $filter) {
                if ($filter['operator'] == 'in') {
                    $data->whereIn($filter['field'], $filter['value']);
                    continue;
                }

                $data->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        $data = $data->get();

        sleep(1.2);

        return new \App\Http\Resources\Pasien\Tarif\DefaultTarifResource(collect($data));
    }
}
