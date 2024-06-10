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
            'no_surat'  => 'required|string',
            'nik'       => 'string|exists:pegawai,nik',
            'karyawans' => 'array',
        ]);

        // karyawans exists in pegawai table
        if ($request->has('karyawans')) {
            foreach ($request->karyawans as $nik) {
                $request->validate([
                    'karyawans.*' => 'exists:pegawai,nik',
                ]);
            }
        }

        // auth user
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();

        // check apakah no_surat ada didalam table penerima undangan
        $penerimaUndangan = \App\Models\RsiaPenerimaUndangan::where('no_surat', $request->no_surat)->get();
        if (!$penerimaUndangan) {
            return ApiResponse::error('resource not found', 'Kegiatan / Undangan tidak ditemukan', 404);
        }

        if ($request->has('nik')) { // petugas yang melakukan
            $request->validate([
                'tipe'      => 'required|string|in:surat/internal,komite/ppi,komite/pmkp,komite/medis,komite/keperawatan,komite/kesehatan,berkas/notulen',
                'model'     => 'required|string|regex:/App\\\\Models\\\\[A-Za-z]+/',
            ]);
    
            // check if model file exists model from request is App\Models\RsiaSuratInternal
            if (!file_exists(app_path('Models/' . str_replace('App\Models\\', '', $request->model) . '.php'))) {
                return ApiResponse::error('Model not found', 'Model ' . $request->model . ' not found', 404);
            }

            // create or update penerima undangan
            $penerimaUndangan = \App\Models\RsiaPenerimaUndangan::updateOrCreate([
                'no_surat' => $request->no_surat,
                'penerima' => $request->nik,
            ], [
                'no_surat' => $request->no_surat,
                'penerima' => $request->nik,
                'tipe'     => $request->tipe,
                'model'    => $request->model,
            ]);

            if ($request->has('karyawans')) {
                foreach ($request->karyawans as $nik) {
                    RsiaKehadiranRapat::firstOrCreate([
                        'nik'      => $nik,
                        'no_surat' => $request->no_surat,
                    ]);
                }
            }
        } else { // request dari client (mobile) ----- user harus login mandiri, absensi tidak dapat diwakilkan
            if (!$penerimaUndangan->contains('penerima', null, $user->id_user)) {
                return ApiResponse::error('user not permitted', 'Anda tidak terdaftar dalam undangan ini', 403);
            }

            $absen = RsiaKehadiranRapat::where('nik', $user->id_user)
                ->where('no_surat', $request->no_surat)
                ->first();

            if ($absen) {
                return ApiResponse::error('resource already exists', 'Anda sudah melakukan absen', 400);
            }

            // insert kehadiran rapat
            RsiaKehadiranRapat::create([
                'nik'      => $user->id_user,
                'no_surat' => $request->no_surat,
            ]);
        }

        return ApiResponse::success('Kehadiran rapat berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $bae64_no_surat
     * @return \Illuminate\Http\Response
     */
    public function show(String $base64_no_surat)
    {
        try {
            $no_surat = base64_decode($base64_no_surat);
        } catch (\Throwable $th) {
            return ApiResponse::error('Invalid base64', 'Invalid base64 string', 400);
        }

        $rsiaKehadiranRapat = RsiaKehadiranRapat::where('no_surat', $no_surat)->get();

        return new \App\Http\Resources\RealDataCollection($rsiaKehadiranRapat);
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
