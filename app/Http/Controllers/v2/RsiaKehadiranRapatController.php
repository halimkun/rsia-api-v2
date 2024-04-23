<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\RsiaKehadiranRapat;
use Illuminate\Http\Request;

class RsiaKehadiranRapatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ApiResponse::error('Action not allowed', "You're not allowed to access this endpoint", 403);
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
        $request->validate([
            'no_surat' => 'required|string',
            'tipe' => 'required|string|in:internal,notulen,komite',
            'model' => 'required|string|regex:/App\\\\Models\\\\[A-Za-z]+/',
            'nik' => 'string|exists:pegawai,nik',
        ]);

        // check apakah no_surat ada didalam table penerima undangan
        $penerimaUndangan = \App\Models\RsiaPenerimaUndangan::where('no_surat', $request->no_surat)->get();
        if (!$penerimaUndangan) {
            return ApiResponse::error('resource not found', 'Kegiatan / Undangan tidak ditemukan', 404);
        }

        // check if model file exists model from request is App\Models\RsiaSuratInternal
        if (!file_exists(app_path('Models/' . str_replace('App\Models\\', '', $request->model) . '.php'))) {
            return ApiResponse::error('Model not found', 'Model ' . $request->model . ' not found', 404);
        }

        if ($request->has('nik')) { // petugas yang melakukan
            // create or update penerima undangan
            $penerimaUndangan = \App\Models\RsiaPenerimaUndangan::updateOrCreate([
                'no_surat' => $request->no_surat,
                'penerima' => $request->nik,
            ], [
                'no_surat' => $request->no_surat,
                'penerima' => $request->nik,
                'tipe' => $request->tipe,
                'model' => $request->model,
            ]);

            $absen = RsiaKehadiranRapat::where('nik', $request->nik)
                ->where('no_surat', $request->no_surat)
                ->first();

            if ($absen) {
                return ApiResponse::error('resource already exists', 'Anda sudah melakukan absen', 400);
            }

            // insert kehadiran rapat
            RsiaKehadiranRapat::create([
                'nik' => $request->nik,
                'no_surat' => $request->no_surat,
            ]);
        } else { // request dari client (mobile)

            if (!$penerimaUndangan->contains('penerima', null, $request->user()->id_user)) {
                return ApiResponse::error('user not permitted', 'Anda tidak terdaftar dalam undangan ini', 403);
            }

            $absen = RsiaKehadiranRapat::where('nik', $request->user()->id_user)
                ->where('no_surat', $request->no_surat)
                ->first();

            if ($absen) {
                return ApiResponse::error('resource already exists', 'Anda sudah melakukan absen', 400);
            }

            // insert kehadiran rapat
            RsiaKehadiranRapat::create([
                'nik' => $request->user()->id_user,
                'no_surat' => $request->no_surat,
            ]);
        }

        return ApiResponse::success('Kehadiran rapat berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RsiaKehadiranRapat  $rsiaKehadiranRapat
     * @return \Illuminate\Http\Response
     */
    public function show(RsiaKehadiranRapat $rsiaKehadiranRapat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RsiaKehadiranRapat  $rsiaKehadiranRapat
     * @return \Illuminate\Http\Response
     */
    public function edit(RsiaKehadiranRapat $rsiaKehadiranRapat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RsiaKehadiranRapat  $rsiaKehadiranRapat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RsiaKehadiranRapat $rsiaKehadiranRapat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RsiaKehadiranRapat  $rsiaKehadiranRapat
     * @return \Illuminate\Http\Response
     */
    public function destroy(RsiaKehadiranRapat $rsiaKehadiranRapat)
    {
        //
    }
}
