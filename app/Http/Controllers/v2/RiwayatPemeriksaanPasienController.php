<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\RealDataCollection;
use App\Http\Resources\RealDataResource;
use Illuminate\Http\Request;

class RiwayatPemeriksaanPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int  $no_rkm_medis
     * @return \Illuminate\Http\Response
     */
    public function index($no_rkm_medis)
    {
        // check if no_rkm_medis exists
        if (\App\Models\Pasien::where('no_rkm_medis', $no_rkm_medis)->doesntExist()) {
            return ApiResponse::notFound("Pasien dengan no_rkm_medis: $no_rkm_medis tidak ditemukan");
        }

        $riwayatPemeriksaan = \App\Models\RegPeriksa::with('dokter', 'caraBayar', 'poliklinik')
            ->where('no_rkm_medis', $no_rkm_medis)
            ->orderBy('tgl_registrasi', 'desc')
            ->paginate();

        return new RealDataCollection($riwayatPemeriksaan);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $no_rkm_medis
     * @param  string  $no_rawat
     * @return \Illuminate\Http\Response
     */
    public function show($no_rkm_medis, $no_rawat, Request $request)
    {
        try {
            $no_rawat = base64_decode($no_rawat);
        } catch (\Exception $e) {
            return ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $no_rawat tidak ditemukan");
        }
        
        // Gunakan firstOrFail untuk mendapatkan hasil atau mengembalikan error jika tidak ditemukan
        $pasien = \App\Models\Pasien::where('no_rkm_medis', $no_rkm_medis)->firstOrFail();
        
        $riwayatPemeriksaanQuery = \App\Models\RegPeriksa::with('pasienSomeData')
            ->where('no_rkm_medis', $no_rkm_medis)
            ->where('no_rawat', $no_rawat);
        
        // Tambahkan eager loading hanya jika diminta
        if ($request->has('include')) {
            $include = explode(',', $request->query('include'));
            $riwayatPemeriksaanQuery->with($include);
        }
        
        // Gunakan firstOrFail untuk mendapatkan hasil atau mengembalikan error jika tidak ditemukan
        $riwayatPemeriksaan = $riwayatPemeriksaanQuery->firstOrFail();
        
        return new RealDataResource($riwayatPemeriksaan);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
