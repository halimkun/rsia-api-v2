<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\RsiaSuratEksternal;
use Illuminate\Http\Request;

class RsiaSuratEksternalController extends Controller
{
    /**
     * Meampilkan data surat eksternal
     * 
     * Semua data surat eksternal yang ada di database akan ditampilkan, data diurutkan berdasarkan tanggal terbit surat. Bersama dengan data surat eksternal, data penanggung jawab surat eksternal juga akan ditampilkan.
     * 
     * @return \App\Http\Resources\Berkas\CompleteCollection
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $data = RsiaSuratEksternal::select($select)
            ->with(['penanggung_jawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10, explode(',', $select), 'page', $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($data);
    }

    /**
     * Menampilkan form untuk membuat surat eksternal baru
     * 
     * > **Catatan:** fungsi ini tidak digunakan dalam API.
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
        $request->validate(self::validationRule(false));

        $last_nomor = RsiaSuratEksternal::select('no_surat')
            ->orderBy('created_at', 'desc')
            ->first();
        $last_nomor = explode('/', $last_nomor->no_surat);
        $last_nomor[0] = str_pad($last_nomor[0] + 1, 3, '0', STR_PAD_LEFT);
        $last_nomor = implode('/', $last_nomor);

        $request->merge([
            'no_surat' => $last_nomor,
        ]);

        try {
            RsiaSuratEksternal::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data surat eksternal',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Berhasil menyimpan data surat eksternal',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RsiaSuratEksternal  $rsiaSuratEksternal
     * @return \Illuminate\Http\Response
     */
    public function show($no_surat, Request $request)
    {
        $decoded_no_surat = base64_decode($no_surat);
        $select = $request->input('select', '*');

        $data = RsiaSuratEksternal::select(explode(',', $select))
            ->with(['penanggung_jawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->where('no_surat', $decoded_no_surat)
            ->first();

        if (!$data) {
            return response()->json([
                'message' => 'Data surat eksternal tidak ditemukan',
            ], 404);
        }

        return new \App\Http\Resources\Berkas\CompleteResource($data);
    }

    /**
     * Menampilkan form untuk mengedit surat eksternal
     *
     * > **Catatan:** fungsi ini tidak digunakan dalam API.
     * 
     * @param  \App\Models\RsiaSuratEksternal  $rsiaSuratEksternal
     * @return \Illuminate\Http\Response
     */
    public function edit(RsiaSuratEksternal  $rsiaSuratEksternal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RsiaSuratEksternal  $rsiaSuratEksternal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $no_surat)
    {
        $decoded_no_surat = base64_decode($no_surat);

        $request->merge([
            'no_surat' => $decoded_no_surat,
        ]);

        $request->validate(self::validationRule(false));

        $data = RsiaSuratEksternal::where('no_surat', $decoded_no_surat)->first();
        if (!$data) {
            return response()->json([
                'message' => 'Data surat eksternal tidak ditemukan',
            ], 404);
        }

        try {
            $data->update($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate data surat eksternal',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Berhasil mengupdate data surat eksternal',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RsiaSuratEksternal  $rsiaSuratEksternal
     * @return \Illuminate\Http\Response
     */
    public function destroy($no_surat)
    {
        $decoded_no_surat = base64_decode($no_surat);

        $data = RsiaSuratEksternal::where('no_surat', $decoded_no_surat)->first();
        if (!$data) {
            return response()->json([
                'message' => 'Data surat eksternal tidak ditemukan',
            ], 404);
        }

        try {
            $data->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus data surat eksternal',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Berhasil menghapus data surat eksternal',
        ]);
    }

    private static function validationRule($withRequired = true)
    {
        return [
            "no_surat"   => ($withRequired ? 'required|' : '') . "string|max:20|regex:/^\d{3}\/B\/S-RSIA\/\d{6}$/",
            "perihal"    => "required|string",
            "alamat"     => "required|string",
            "tgl_terbit" => "required|date",
            "pj"         => "required|string|exists:pegawai,nik",
            "tanggal"    => "required|date_format:Y-m-d H:i:s",
        ];
    }
}
