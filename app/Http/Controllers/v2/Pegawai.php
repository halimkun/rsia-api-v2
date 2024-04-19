<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pegawai\CompleteResource;
use Illuminate\Http\Request;

class Pegawai extends Controller
{
    /**
     * Menampilkan daftar pegawai.
     * 
     * @queryParam page int Halaman yang ditampilkan. Defaults to 1. Example: 1
     * @queryParam select string Kolom yang ingin ditampilkan. Defaults to '*'. Example: nik,nama
     *
     * @return \App\Http\Resources\Pegawai\CompleteCollection
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $select = $request->query('select', '*');

        $pegawai = \App\Models\Pegawai::paginate(10, explode(',', $select), 'page', $page);

        return new \App\Http\Resources\Pegawai\CompleteCollection($pegawai);
    }

    /**
     * Menampilkan form untuk membuat pegawai baru.
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
     * Menyimpan pegawai baru.
     * 
     * > catatan: data key pada body request harus sesuai dengan field pada tabel pegawai.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(self::validationRule());
        
        try {
            // TODO : upload photo pegawai
            \App\Models\Pegawai::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create pegawai: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data pegawai berhasil ditambahkan'
        ]);
    }

    /**
     * Menampilkan data pegawai berdasarkan NIK.
     * 
     * @queryParam select string Kolom yang ingin ditampilkan. Defaults to '*'. Example: nik,nama
     *
     * @param  string  $id NIK pegawai. Example: 3.928.0623
     * @return \App\Http\Resources\Pegawai\CompleteResource
     */
    public function show($id, Request $request)
    {
        $select = $request->query('select', '*');

        $pegawai = \App\Models\Pegawai::select(explode(',', $select))->find($id);
        if (!$pegawai) {
            return response()->json([
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        return \App\Http\Resources\Pegawai\CompleteResource::make($pegawai);
    }

    /**
     * Menampilkan form untuk mengedit pegawai.
     *
     * > fungsi ini tidak digunakan dalam API.
     * 
     * @param  string  $id Nik pegawai
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update pegawai berdasarkan NIK.
     * 
     * > catatan: data key pada body request harus sesuai dengan field pada tabel pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id Nik pegawai. Example: 3.928.0623
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(self::validationRule(false));
        
        $pegawai = \App\Models\Pegawai::find($id);
        if (!$pegawai) {
            return response()->json([
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        try {
            // TODO : upload photo pegawai
            $pegawai->update($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update pegawai: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data pegawai berhasil diupdate'
        ]);
    }

    /**
     * Menghapus pegawai berdasarkan NIK.
     * 
     * > catatan : data yang di hapus tidak dapat dikembalikan.
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pegawai = \App\Models\Pegawai::find($id);
        if (!$pegawai) {
            return response()->json([
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        try {
            $pegawai->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete pegawai: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data pegawai berhasil dihapus'
        ]);
    }

    private static function validationRule($withRequired = true)
    {
        // TODO : add validation gor photo
        return [
            "nik"            => "required|string|regex:/^\d{1,3}\.\d{1,3}\.\d{1,4}$/",
            "nama"           => "required|string",
            "jk"             => "required|string|in:Wanita,Pria",
            "jbtn"           => "required|string",
            "jnj_jabatan"    => "required|string|exists:jnj_jabatan,kode",
            "kode_kelompok"  => ($withRequired ? "required|" : "") . "string|exists:kelompok,kode_kelompok",
            "kode_resiko"    => "required|string|exists:resiko_kerja,kode_resiko",
            "kode_emergency" => ($withRequired ? "required|" : "") . "string|exists:emergency_index,kode_emergency",
            "status_koor"    => ($withRequired ? "required|" : "") . "string|in:0,1",
            "departemen"     => "required|string|exists:departemen,dep_id",
            "bidang"         => "required|string|exists:bidang,nama",
            "stts_wp"        => ($withRequired ? "required|" : "") . "string|exists:stts_wp,stts",
            "stts_kerja"     => ($withRequired ? "required|" : "") . "string|exists:stts_kerja,stts",
            "npwp"           => ($withRequired ? "required|" : "") . "string",
            "pendidikan"     => ($withRequired ? "required|" : "") . "string|exists:pendidikan,tingkat",
            "gapok"          => ($withRequired ? "required|" : "") . "numeric",
            "tmp_lahir"      => ($withRequired ? "required|" : "") . "string",
            "tgl_lahir"      => ($withRequired ? "required|" : "") . "date",
            "alamat"         => ($withRequired ? "required|" : "") . "string",
            "kota"           => ($withRequired ? "required|" : "") . "string",
            "mulai_kerja"    => "required|date",
            "ms_kerja"       => ($withRequired ? "required|" : "") . "string|in:<1,PT,FT>1",
            "indexins"       => ($withRequired ? "required|" : "") . "string|exists:departemen,dep_id",
            "bpd"            => ($withRequired ? "required|" : "") . "string|exists:bank,namabank",
            "rekening"       => ($withRequired ? "required|" : "") . "string",
            "stts_aktif"     => "required|string|in:AKTIF,CUTI,KELUAR,TENAGA LUAR",
            "wajibmasuk"     => ($withRequired ? "required|" : "") . "integer",
            "pengurang"      => ($withRequired ? "required|" : "") . "numeric",
            "indek"          => ($withRequired ? "required|" : "") . "integer",
            "mulai_kontrak"  => ($withRequired ? "required|" : "") . "date",
            "cuti_diambil"   => ($withRequired ? "required|" : "") . "integer",
            "dankes"         => ($withRequired ? "required|" : "") . "numeric",
            "photo"          => ($withRequired ? "required|" : "") . "string",
            "no_ktp"         => ($withRequired ? "required|" : "") . "string",
        ];
    }
}
