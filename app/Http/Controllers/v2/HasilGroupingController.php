<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

class HasilGroupingController extends Controller
{
    /**
     * Get the latest grouping result for a SEP
     * 
     * @param string $sep
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($sep)
    {
        $hasil = \App\Models\InacbgGropingStage12::where('no_sep', $sep)->first();
        return ApiResponse::successWithData($hasil, 'Data berhasil diambil');
    }
}
