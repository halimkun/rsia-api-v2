<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Orion\Http\Requests\Request as OrionRequest;

class RsiaBerkasKomiteMedisController extends Controller
{
    /**
     * Menampilkan semua data
     * 
     * Menampilkan semua data yang ada pada tabel berkas komite medis. data akan diurutkan berdasarkan tanggal terbit data secara descending. dan diformat dalam bentuk paginasi.
     * 
     * @return \App\Http\Resources\Berkas\Komite\CompleteCollection
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $data = \App\Models\RsiaBerkasKomiteMedis::select(array_map('trim', explode(',', $select)))
            ->with('penanggungjawab')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

        return new \App\Http\Resources\Berkas\Komite\CompleteCollection($data);
    }

    /**
     * Pencarian data
     * 
     * Fitur pencarian data ini memanfaatkan basis kode laravel orion, dimana parameter yang bisa digunakan seusai dengan yang ada didalam dokumentasi orion.
     * https://tailflow.github.io/laravel-orion-docs/v2.x/guide/search.html
     * 
     * @return \App\Http\Resources\Berkas\Komite\CompleteCollection
     */
    public function search(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $orion_request = new OrionRequest($request->all());
        $actionMethod = $request->route()->getActionMethod();

        $fd = new \App\Services\GetFilterData(new \App\Models\RsiaBerkasKomiteMedis(), $orion_request, $actionMethod);
        $query = $fd->apply();

        $data = $query->select(array_map('trim', explode(',', $select)))
            ->with(['penanggung_jawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($data);
    }

    /**
     * Menampilkan formulir untuk membuat sumber daya baru.
     * 
     * > Catatan : Endpoint ini tidak digunakan dalam API.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan data baru
     * 
     * Menyimpan data baru berdasarkan request yang diterima. nomor otomatis di generate berdasarkan tanggal terbit data dengan kondisi menambahkan 1 nomor dari nomor terakhir yang ada pada data dengan tahun yang sesuai dengan tahun pada tanggal terbit.
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pj'         => 'required|string|exists:pegawai,nik',
            'perihal'    => 'required|string',
            'tgl_terbit' => 'required|date',
        ]);

        $nomor = \App\Helpers\komite\LastNomor::get(new \App\Models\RsiaBerkasKomiteMedis(), $request->tgl_terbit);
        $buildedNomor = [
            str_pad($nomor, 3, '0', STR_PAD_LEFT),
            'KOMED-RSIA',
            \Carbon\Carbon::createFromFormat('Y-m-d', $request->tgl_terbit)->format('dmy'),
        ];

        $request->merge([
            'nomor' => $nomor,
            'no_surat' => implode('/', $buildedNomor),
        ]);

        try {
            DB::transaction(function () use ($request) {
                \App\Models\RsiaBerkasKomiteMedis::create($request->all());
            });
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error("failed to save data", $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success("data saved successfully");
    }

    /**
     * Menampilkan detail data
     * 
     * Menampilkan detail data berdasarkan nomor dan tanggal (yyyy-mm-dd) terbit data yang di encode menjadi base64. contoh : 32.2024-02-30
     *
     * @param  String $base64NomorTglTerbit base64 encoded nomor dan tanggal (yyyy-mm-dd) terbit (43.2024-02-30)
     * @return \App\Http\Resources\Berkas\Komite\CompleteResource
     */
    public function show($base64NomorTglTerbit)
    {
        if (!base64_decode($base64NomorTglTerbit, true)) {
            return \App\Helpers\ApiResponse::error("Invalid parameter", "Parameter tidak valid, pastikan parameter adalah base64 encoded dari nomor dan tanggal misal : 53.2024-03-28", 400);
        }

        $identifier = explode('.', base64_decode($base64NomorTglTerbit));

        if (!\Carbon\Carbon::createFromFormat('Y-m-d', $identifier[1])) {
            return \App\Helpers\ApiResponse::error("Invalid date format", "Format tanggal tidak valid (YYYY-MM-DD)", 400);
        }

        $data = \App\Models\RsiaBerkasKomiteMedis::where('nomor', $identifier[0])
            ->where('tgl_terbit', $identifier[1])
            ->with('penanggungjawab')
            ->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "Data dengan detail tersebut tidak ditemukan", 404);
        }

        return new \App\Http\Resources\Berkas\Komite\CompleteResource($data);
    }

    /**
     * Menampilkan formulir untuk mengedit sumber daya tertentu.
     * 
     * > Catatan : Endpoint ini tidak digunakan dalam API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Perbarui sumber daya tertentu di penyimpanan.
     * 
     * dari endpoint ini anda bisa mengupdate data berdasarkan nomor dan tanggal terbit data yang akan terupdate adalah pj, perihaal dan status. 
     * dimana nomor dan tanggal terbit tidak bisa diupdate, hal ini untuk menjaga konsistensi data. 
     * 
     * untuk mengupdate data diperlukan query parameter berupa base64 dari nomor dan tanggal terbit (yyyy-mm-dd) data yang akan diupdate, contoh : 32.2024-02-30. selain itu nomor dan tanggal terbit juga harus diisi pada body request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $base64NomorTglTerbit base64 encoded nomor dan tanggal terbit (43.2024-02-30)
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $base64NomorTglTerbit)
    {
        if (!base64_decode($base64NomorTglTerbit, true)) {
            return \App\Helpers\ApiResponse::error("Invalid parameter", "Parameter tidak valid, pastikan parameter adalah base64 encoded dari nomor dan tanggal misal : 53.2024-03-28", 400);
        }

        $identifier = explode('.', base64_decode($base64NomorTglTerbit));
        $request->validate([
            'nomor'      => 'required',
            'tgl_terbit' => 'required|date',

            'pj'         => 'required|string|exists:pegawai,nik',
            'perihal'    => 'required|string',
            'status'     => 'string|in:1,0',
        ]);

        // dd($identifier, $request->all());

        if (!\Carbon\Carbon::createFromFormat('Y-m-d', $identifier[1])) {
            return \App\Helpers\ApiResponse::error("Invalid date format", "Format tanggal tidak valid (YYYY-MM-DD)", 400);
        }

        if ($identifier[0] != $request->nomor || $identifier[1] != $request->tgl_terbit) {
            return \App\Helpers\ApiResponse::error("Invalid request", "Nomor atau tanggal terbit tidak valid", 400);
        }

        $data = \App\Models\RsiaBerkasKomiteMedis::where('nomor', $request->nomor)
            ->where('tgl_terbit', $request->tgl_terbit)
            ->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "Data dengan detail tersebut tidak ditemukan", 404);
        }

        try {
            DB::transaction(function () use ($request, $data) {
                $data->update($request->except(['nomor', 'tgl_terbit']));
            });
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error("failed to update data", $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success("data updated successfully");
    }

    /**
     * Menghapus data 
     * 
     * Menghapus data berdasarkan nomor dan tanggal terbit (yyyy-mm-dd) data yang di encode menjadi base64. contoh : 32.2024-02-30
     * metode ini akan menghapus data secara permanen dari database, maka pastikan data yang akan dihapus tidak diperlukan lagi.
     * 
     * nomor pada data yang sudah dihapus tidak akan di gunakan lagi, dan akan di skip ketika generate nomor otomatis.
     * 
     * > **Perhatian** : Metode ini tidak bisa di rollback.
     * > Jika data yang dihapus masih diperlukan, maka gunakan metode update untuk mengubah status data menjadi tidak aktif (0), dengan demikian data tidak akan ditampilkan pada aplikasi dan masih bisa diakses jika diperlukan.
     *
     * @param  string $base64NomorTglTerbit base64 encoded nomor dan tanggal terbit (43.2024-02-30)
     * @return \Illuminate\Http\Response
     */
    public function destroy($base64NomorTglTerbit)
    {
        if (!base64_decode($base64NomorTglTerbit, true)) {
            return \App\Helpers\ApiResponse::error("Invalid parameter", "Parameter tidak valid, pastikan parameter adalah base64 encoded dari nomor dan tanggal misal : 53.2024-03-28", 400);
        }

        $identifier = explode('.', base64_decode($base64NomorTglTerbit));

        if (!\Carbon\Carbon::createFromFormat('Y-m-d', $identifier[1])) {
            return \App\Helpers\ApiResponse::error("Invalid date format", "Format tanggal tidak valid (YYYY-MM-DD)", 400);
        }

        $data = \App\Models\RsiaBerkasKomiteMedis::where('nomor', $identifier[0])
            ->where('tgl_terbit', $identifier[1])
            ->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "Data dengan detail tersebut tidak ditemukan", 404);
        }

        try {
            DB::transaction(function () use ($data) {
                $data->delete();
            });
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error("failed to delete data", $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success("data deleted successfully");
    }
}
