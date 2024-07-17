<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerkasPegawaiController  extends Controller
{
    /**
     * Menampilkan daftar berkas pegawai.
     *
     * @param  string  $nik
     * @return \App\Http\Resources\Berkas\CompleteCollection
     */
    public function index($nik, Request $request)
    {
        $page = $request->query('page', 1);
        $select = $request->query('select', '*');

        $berkas = \App\Models\BerkasPegawai::where('nik', $nik)->paginate(10, explode(',', $select), 'page', $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($berkas);
    }

    /**
     * Menampilkan form untuk membuat berkas pegawai baru.
     *
     * > fungsi ini tidak digunakan dalam API.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan berkas pegawai baru.
     *
     * @param  string  $nik
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($nik, Request $request)
    {
        $request->validate(self::validationRule());

        try {
            // TODO : tambahkan upload file, jika file gagal diupload maka berkas tidak disimpan
            \App\Models\BerkasPegawai::create($request->all());
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Failed to save data', 'store_failed', $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success('Data saved successfully');
    }

    /**
     * Menampilkan detail berkas pegawai.
     *
     * @param  string  $nik
     * @param  string  $kode_berkas
     * @return \Illuminate\Http\Response
     */
    public function show($nik, $kode_berkas, Request $request)
    {
        $select = $request->query('select', '*');

        $berkas = \App\Models\BerkasPegawai::select(explode(',', $select))->where('nik', $nik)->where('kode_berkas', $kode_berkas)->first();
        if (!$berkas) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        return \App\Http\Resources\Berkas\CompleteResource::make($berkas);
    }

    /**
     * Menampilkan form untuk mengedit berkas pegawai.
     *
     * > fungsi ini tidak digunakan dalam API.
     * 
     * @param  string  $kode_berkas
     * @return \Illuminate\Http\Response
     */
    public function edit($kode_berkas)
    {
    }

    /**
     * Update berkas pegawai.
     * 
     * > catatan: data key pada body request harus sesuai dengan field pada tabel berkas_pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $kode_berkas
     * @return \Illuminate\Http\Response
     */
    public function update($nik, Request $request, $kode_berkas)
    {
        $request->validate(self::validationRule(false));

        $berkas = \App\Models\BerkasPegawai::where('nik', $nik)->where('kode_berkas', $kode_berkas)->exists();
        if (!$berkas) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        try {
            // TODO : tambahkan upload file, jika file gagal diupload maka berkas tidak disimpan
            \App\Models\BerkasPegawai::where('nik', $nik)->where('kode_berkas', $kode_berkas)->update($request->all());
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Failed to update data', 'update_failed', $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success('Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $kode_berkas
     * @return \Illuminate\Http\Response
     */
    public function destroy($nik, $kode_berkas)
    {
        $berkas = \App\Models\BerkasPegawai::where('nik', $nik)->where('kode_berkas', $kode_berkas)->exists();
        if (!$berkas) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        try {
            // TODO : tambahkan delete file, jika file gagal dihapus maka berkas tidak dihapus
            \App\Models\BerkasPegawai::where('nik', $nik)->where('kode_berkas', $kode_berkas)->delete();
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Failed to delete data', 'delete_failed', $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success('Data deleted successfully');
    }

    private static function validationRule($withRequired = true)
    {
        return [

            "nik"         => "required|string|exists:pegawai,nik",
            "tgl_uploud"  => "required|date",
            "kode_berkas" => "required|string|exists:master_berkas_pegawai,kode",
            "berkas"      => "required|string",
        ];
    }
}
