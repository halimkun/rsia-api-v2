<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\RsiaSuratEksternal;
use App\Services\GetFilterData;
use Illuminate\Http\Request;
use Orion\Http\Requests\Request as OrionRequest;

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

        $data = RsiaSuratEksternal::select(array_map('trim', explode(',', $select)))
            ->with(['penanggungJawabSimple' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

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
            ->whereYear('tgl_terbit', \Carbon\Carbon::parse($request->tgl_terbit)->year)
            ->first();
        
        if ($last_nomor) {
            $last_nomor = explode('/', $last_nomor->no_surat);
            $last_nomor[0] = str_pad($last_nomor[0] + 1, 3, '0', STR_PAD_LEFT);
            $last_nomor[3] = \Carbon\Carbon::parse($request->tgl_terbit)->format('dmy');
            $last_nomor = implode('/', $last_nomor);
        } else {
            $last_nomor = '001/B/S-RSIA/' . \Carbon\Carbon::parse($request->tgl_terbit)->format('dmy');
        }

        $request->merge([
            'no_surat' => $last_nomor,
        ]);

        try {
            RsiaSuratEksternal::create($request->all());
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("STORE DATA FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error('Gagal menyimpan data surat eksternal', 'store_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("STORED", 'info', ['data' => $request->all()]);
        return \App\Helpers\ApiResponse::success('Berhasil menyimpan data surat eksternal');
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

        $data = RsiaSuratEksternal::select(array_map('trim', explode(',', $select)))
            ->with(['penanggungJawabSimple' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->where('no_surat', $decoded_no_surat)
            ->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
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
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        $oldData = $data->toArray();

        if ($request->tgl_terbit != $data->tgl_terbit) {
            $exp_nomor = explode('/', $data->no_surat);
            $exp_nomor[3] = \Carbon\Carbon::parse($request->tgl_terbit)->format('dmy');
            
            $request->merge([
                'no_surat' => implode('/', $exp_nomor),
            ]);
        }

        try {
            // $data->update($request->all());
            RsiaSuratEksternal::where('no_surat', $oldData['no_surat'])->update($request->except('_method'));
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("UPDATE FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage(), 'old_data' => $oldData]);
            return \App\Helpers\ApiResponse::error('Gagal mengupdate data surat eksternal', 'update_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("UPDATED", 'info', ['data' => $request->all(), 'old_data' => $oldData]);
        return \App\Helpers\ApiResponse::success('Berhasil mengupdate data surat eksternal');
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
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        try {
            $data->delete();
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("DELETE FAILED", 'error', ['data' => $data]);
            return \App\Helpers\ApiResponse::error('Gagal menghapus data surat eksternal', 'failed_delete', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("DELETED", 'info', ['data' => $data]);
        return \App\Helpers\ApiResponse::success('Berhasil menghapus data surat eksternal');
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
