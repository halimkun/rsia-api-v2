<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dokterOnPoli = \App\Models\JadwalPoli::select('kd_dokter')->with('dokter')->groupBy('kd_dokter')->get();
        $poliklinik = \App\Models\JadwalPoli::select('kd_poli')->with('poliklinik')->groupBy('kd_poli')->get();

        $filterApplied = $request->anyFilled(['kd_dokter', 'kd_poli', 'tgl_registrasi']);

        if ($filterApplied) {
            $query = \App\Models\RegPeriksa::with(['pasienSomeData', 'dokter', 'poliklinik'])->where('tgl_registrasi', '>=', \Carbon\Carbon::now()->toDateString());

            if ($request->filled('kd_dokter')) {
                $query->where('kd_dokter', $request->kd_dokter);
            }

            if ($request->filled('kd_poli')) {
                $query->where('kd_poli', $request->kd_poli);
            }

            if ($request->filled('tgl_registrasi')) {
                $query->where('tgl_registrasi', $request->tgl_registrasi);
            }

            $registrasi = $query->orderBy('tgl_registrasi', 'asc')->paginate(10);
        } else {
            // blank pagination data if no filter applied 
            $registrasi =  new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return view('app.notification.jadwal-dokter', [
            'dokters'     => $dokterOnPoli,
            'polikliniks' => $poliklinik,
            'registrasi'  => $registrasi
        ]);
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
        // validate : nik,  polinklinik, tanggal, jam mulai, jam selesai, tidak ditentukan
        $request->validate([
            'kd_dokter'        => 'required|exists:dokter,kd_dokter',
            'kd_poli'          => 'required|exists:poliklinik,kd_poli',
            'tgl_registrasi'   => 'required|date',
            'tanggal'          => 'date',
            'jam_mulai'        => 'required|date_format:H:i',
            'jam_selesai'      => 'date_format:H:i',
        ]);

        $dispatchableData = collect([
            'nik' => $request->kd_dokter,
            'parse' => [
                "tanggal" => $request->tgl_sama == 'on' ? $request->tgl_registrasi : $request->tanggal,
                "mulai"   => $request->jam_mulai,
                "selesai" => $request->tidak_ditentukan == 'on' ? "Selesai" : $request->jam_selesai
            ]
        ]);

        $pasien = \App\Models\RegPeriksa::select('no_rkm_medis');
        if ($request->filled('kd_dokter')) {
            $pasien->where('kd_dokter', $request->kd_dokter);
        }

        if ($request->filled('kd_poli') && !in_array($request->kd_poli, ['all', '', '-', null])) {
            $pasien->where('kd_poli', $request->kd_poli);
        }

        if ($request->filled('tgl_registrasi')) {
            $pasien->where('tgl_registrasi', $request->tgl_registrasi);
        }

        $pasien = $pasien->get();
        $noRkmMedis = $pasien->pluck('no_rkm_medis')->toArray();

        \App\Jobs\JadwalPraktikDokter::dispatch('perubahan_jadwal_dokter', $noRkmMedis, $dispatchableData);

        return redirect()->route('app.notification.jadwal-dokter')->with('success', 'Notifikasi berhasil dikirim');
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
