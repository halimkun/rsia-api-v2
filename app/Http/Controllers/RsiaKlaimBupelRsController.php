<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\RsiaBupelKlaimRs;
use Illuminate\Http\Request;

class RsiaKlaimBupelRsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ApiResponse::successWithData(RsiaBupelKlaimRs::first(), 'Data berhasil diambil', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RsiaBupelKlaimRs  $rsiaKlaimBupelRs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // validate month YYYY-MM
        $request->validate([
            'bulan' => 'required|date|date_format:Y-m',
        ]);

        \DB::transaction(function () use ($request) {
            RsiaBupelKlaimRs::truncate();
            RsiaBupelKlaimRs::create($request->all());
        }, 5);

        return ApiResponse::success('Data berhasil diupdate');
    }
}
