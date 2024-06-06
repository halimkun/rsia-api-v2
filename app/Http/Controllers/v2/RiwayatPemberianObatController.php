<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPemberianObatController extends Controller
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

        $riwayat = \App\Models\ResepObat::with('detail.obat', 'detail.aturanPakai')->where('no_rawat', $noRawat)->paginate(10);

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
}
