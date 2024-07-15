<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPemeriksaanRalan extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($rm, $noRawat)
    {
        // check pasien exists
        if (!\App\Models\Pasien::where('no_rkm_medis', $rm)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Pasien dengan no_rkm_medis: $rm tidak ditemukan");
        }

        try {
            $noRawat = base64_decode($noRawat);
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        if (!\App\Models\RegPeriksa::where('no_rawat', $noRawat)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        $riwayat = \App\Models\PemeriksaanRalan::with('petugas')->where('no_rawat', $noRawat)
            ->orderBy('no_rawat', 'desc')
            ->orderBy('tgl_perawatan', 'desc')
            ->orderBy('jam_rawat', 'desc')
            ->paginate(10);

        return new \App\Http\Resources\RealDataCollection($riwayat);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Get tensi from riwayat pemeriksaan ranap
     * 
     * @param string $rm
     * @param string $noRawat
     * 
     * @return \App\Helpers\ApiResponse
     * */
    public function getTensi($rm, $noRawat)
    {
        // check pasien exists
        if (!\App\Models\Pasien::where('no_rkm_medis', $rm)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Pasien dengan no_rkm_medis: $rm tidak ditemukan");
        }

        try {
            $noRawat = base64_decode($noRawat);
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        if (!\App\Models\RegPeriksa::where('no_rawat', $noRawat)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        $riwayat = \App\Models\PemeriksaanRanap::select('tensi')->where('no_rawat', $noRawat)
            ->where('tensi', '!=', '')->where('tensi', '!=', '-')->where('tensi', '!=', null)
            ->orderBy('no_rawat', 'desc')->orderBy('tgl_perawatan', 'desc')->orderBy('jam_rawat', 'desc')
            ->first();

        return new \App\Http\Resources\RealDataResource($riwayat);
    }
}
