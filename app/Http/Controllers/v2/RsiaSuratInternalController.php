<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\RsiaSuratInternal;
use Illuminate\Http\Request;
use Orion\Http\Requests\Request as OrionRequest;

class RsiaSuratInternalController extends Controller
{
    /**
     * Meampilkan data surat internal
     * 
     * Semua data surat internal yang ada di database akan ditampilkan, data diurutkan berdasarkan tanggal terbit surat. Bersama dengan data surat internal, data penanggung jawab surat internal juga akan ditampilkan.
     * 
     * @return \App\Http\Resources\Berkas\CompleteCollection
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $data = RsiaSuratInternal::select(array_map('trim', explode(',', $select)))
            ->with(['penanggung_jawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($data);
    }

    public function search(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $orion_request = new OrionRequest($request->all());
        $actionMethod = $request->route()->getActionMethod();

        $fd = new \App\Services\GetFilterData(new \App\Models\RsiaSuratEksternal(), $orion_request, $actionMethod);
        $query = $fd->apply();

        $data = $query->select(array_map('trim', explode(',', $select)))
            ->with(['penanggung_jawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($data);
    }

    /**
     * Menampilkan form untuk membuat surat internal baru
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
     * Menyimpan surat internal baru
     * 
     * Menyimpan data surat internal baru yang dibuat oleh pengguna. Data yang disimpan berupa nomor surat, perihal, tempat, pj, tanggal terbit, catata, status, dan file surat.
     * > data key pada body request harus sesuai dengan field pada tabel pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(self::validationRule(false));

        $last_nomor = RsiaSuratInternal::select('no_surat')
            ->orderBy('created_at', 'desc')
            ->first();
        $last_nomor = explode('/', $last_nomor->no_surat);
        $last_nomor[0] = str_pad($last_nomor[0] + 1, 3, '0', STR_PAD_LEFT);
        $last_nomor = implode('/', $last_nomor);

        $request->merge([
            'no_surat' => $last_nomor,
            'status' => 'pengajuan',
        ]);

        try {
            RsiaSuratInternal::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create surat internal: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data surat internal berhasil ditambahkan'
        ]);
    }

    /**
     * Menampilkan data surat internal berdasarkan nomor surat
     * 
     * Menampilkan spesifik data surat internal berdasarkan nomor surat yang diberikan oleh pengguna. Data yang ditampilkan berupa nomor surat, perihal, tempat, pj, tanggal terbit, tanggal, catatan, status, dan penanggung jawab surat internal.
     * 
     * > **Catatan:** nomor surat yang digunakan harus di encode base64 terlebih dahulu.
     * > **Contoh:** MDU4L0EvUy1SU0lBLzAzMDQyNA==
     * > dari nilai asli 058/A/S-RSIA/030424
     *
     * @param  string $no_surat Nomor surat internal (yang di encode base64) yang ingin ditampilkan. Example: MDU4L0EvUy1SU0lBLzAzMDQyNA==
     * @return \Illuminate\Http\Response
     */
    public function show($no_surat, Request $request)
    {
        $decoded_no_surat = base64_decode($no_surat);
        $select = $request->input('select', '*');

        $data = RsiaSuratInternal::select(array_map('trim', explode(',', $select)))
            ->with(['penanggung_jawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->where('no_surat', $decoded_no_surat)
            ->first();

        if (!$data) {
            return response()->json([
                'message' => 'Data surat internal tidak ditemukan'
            ], 404);
        }

        return new \App\Http\Resources\Berkas\CompleteResource($data);
    }

    /**
     * Menampilkan form untuk mengedit surat internal
     *
     * > **Catatan:** fungsi ini tidak digunakan dalam API.
     * 
     * @param  \App\Models\RsiaSuratInternal  $rsiaSuratInternal
     * @return \Illuminate\Http\Response
     */
    public function edit(RsiaSuratInternal $rsiaSuratInternal)
    {
        //
    }

    /**
     * Mengupdate data surat internal
     * 
     * Mengupdate data surat internal berdasarkan nomor surat yang diberikan oleh pengguna. Data yang dapat diupdate berupa perihal, tempat, pj, tanggal terbit, catatan, dan status.
     * 
     * > **Catatan:** nomor surat yang digunakan harus di encode base64 terlebih dahulu.
     * > **Contoh:** MDU4L0EvUy1SU0lBLzAzMDQyNA==
     * > dari nilai asli 058/A/S-RSIA/030424
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $no_surat Nomor surat internal (yang di encode base64) yang ingin diupdate. Example: MDU4L0EvUy1SU0lBLzAzMDQyNA==
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $no_surat)
    {
        $decoded_no_surat = base64_decode($no_surat);

        $request->merge([
            'no_surat' => $decoded_no_surat,
        ]);

        $request->validate(self::validationRule(false));

        $data = RsiaSuratInternal::where('no_surat', $decoded_no_surat)->first();
        if (!$data) {
            return response()->json([
                'message' => 'Data surat internal tidak ditemukan'
            ], 404);
        }

        try {
            $data->update($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update surat internal: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data surat internal berhasil diupdate'
        ]);
    }

    /**
     * Menghapus data surat internal
     *
     * @param  \App\Models\RsiaSuratInternal  $rsiaSuratInternal
     * @return \Illuminate\Http\Response
     */
    public function destroy($no_surat)
    {
        $decoded_no_surat = base64_decode($no_surat);

        $data = RsiaSuratInternal::where('no_surat', $decoded_no_surat)->first();
        if (!$data) {
            return response()->json([
                'message' => 'Data surat internal tidak ditemukan'
            ], 404);
        }

        try {
            $data->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete surat internal: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data surat internal berhasil dihapus'
        ]);
    }

    /**
     * Aturan validasi untuk data surat internal
     * 
     * Dengan menerapkan aturan validasi ini, data yang dikirimkan oleh pengguna akan dicek kebenarannya. Jika data tidak sesuai dengan aturan validasi, maka data tidak akan disimpan ke dalam database.
     * 
     * @param  boolean  $withRequired
     * @return array 
     */
    private static function validationRule($withRequired = true)
    {
        return [
            "no_surat"   => ($withRequired ? 'required|' : '') . "string|max:20|regex:/^\d{3}\/A\/S-RSIA\/\d{6}$/",
            "perihal"    => "required|string",
            "tempat"     => "required|string|max:100",
            "pj"         => "required|string|exists:pegawai,nik",
            "tgl_terbit" => "required|date",
            "tanggal"    => "required|date_format:Y-m-d H:i:s",
            "catatan"    => "string",
            "status"     => ($withRequired ? 'required|' : '') . "string|in:pengajuan,disetujui,ditolak,batal",
        ];
    }
}
