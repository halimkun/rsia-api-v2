<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $now = \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->locale('id');
        $nik = $request->nik;

        // get pegawai by nik
        $pegawai = \App\Models\Pegawai::where('nik', $nik)->first();

        // check pegawai
        if (!$pegawai) {
            return ApiResponse::error('Resource not found', 'resource_not_found', null, 404);
        }

        $currentDay = date('d');
        if (substr($currentDay, 0, 1) == 0) {
            $currentDay = substr($currentDay, 1);
        }

        // Get jadwal pegawai
        $jadwal = \App\Models\JadwalPegawai::select("h" . $currentDay . ' as shift')
            ->where('id', $pegawai->id)
            ->where('bulan', date('m'))
            ->where('tahun', date('Y'))
            ->first();

        if (!$jadwal) {
            return ApiResponse::error('Resource not found', 'resource_not_found', null, 404);
        }

        $jam_masuk = \App\Models\JamMasuk::where('shift', $jadwal->shift)->first();

        $detailed = array_merge($jadwal->toArray(), [
            'jam_masuk' => $jam_masuk->toArray()
        ]);

        // return
        return new \App\Http\Resources\RealDataResource($detailed);
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
