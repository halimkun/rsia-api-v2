<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = \App\Models\Pasien::select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir')
            ->orderBy('nm_pasien', 'asc')
            ->paginate(10);

        return new \App\Http\Resources\Pasien\PasienCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = \App\Models\Pasien::where('no_rkm_medis', $id)->first();

        if (!$data) {
            return ApiResponse::notFound("Data pasien tidak ditemukan");
        }

        return new \App\Http\Resources\Pasien\PasienResource($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return ApiResponse::error("unimplemented", 501);
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
        $request->validate(self::validationRule(true));

        $data = \App\Models\Pasien::where('no_rkm_medis', $id)->first();

        if (!$data) {
            return ApiResponse::notFound("Data pasien tidak ditemukan");
        }

        try {
            \DB::transaction(function () use ($request, $data) {
                $data->update($request->all());
            });
        } catch (\Exception $e) {
            return ApiResponse::error("update failed", $e->getMessage(), 500);
        }

        return ApiResponse::success("Data pasien berhasil diupdate");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    private static function validationRule($withRequired = false)
    {
        return [
            'nm_pasien'         => 'required|string',
            'no_ktp'            => 'nullable|string',
            'jk'                => 'nullable|string|in:L,P',
            'tmp_lahir'         => 'nullable|string',
            'tgl_lahir'         => 'nullable|date',
            'nm_ibu'            => 'required|string',
            'alamat'            => 'nullable|string',
            'gol_darah'         => 'nullable|string',
            'pekerjaan'         => 'nullable|string',
            'stts_nikah'        => 'nullable|string',
            'agama'             => 'nullable|string',
            'tgl_daftar'        => 'nullable|date',
            'no_tlp'            => 'nullable|string',
            'umur'              => 'required|string',
            'pnd'               => 'required|string',
            'keluarga'          => 'nullable|string',
            'namakeluarga'      => 'required|string',
            'kd_pj'             => 'required|string|exists:penjab,kd_pj',
            'no_peserta'        => 'nullable|string',
            'kd_kel'            => 'required|integer|exists:kelurahan,kd_kel',
            'kd_kec'            => 'required|integer|exists:kecamatan,kd_kec',
            'kd_kab'            => 'required|integer|exists:kabupaten,kd_kab',
            'pekerjaanpj'       => 'required|string',
            'alamatpj'          => 'required|string',
            'kelurahanpj'       => 'required|string',
            'kecamatanpj'       => 'required|string',
            'kabupatenpj'       => 'required|string',
            'perusahaan_pasien' => 'required|string|exists:perusahaan_pasien,kd_perusahaan',
            'suku_bangsa'       => 'required|integer|exists:suku_bangsa,id',
            'bahasa_pasien'     => 'required|integer|exists:bahasa_pasien,id',
            'cacat_fisik'       => 'required|integer|exists:cacat_fisik,id',
            'email'             => 'required|email',
            'nip'               => 'required|string',
            'kd_prop'           => 'required|integer|exists:propinsi,kd_prop',
            'propinsipj'        => 'required|string',
        ];
    }
}
