<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\RsiaSuratMasuk;
use Illuminate\Http\Request;
use Orion\Http\Requests\Request as OrionRequest;

class RsiaSuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->input("page", 1);
        $select = $request->input("select", "*");

        $data = RsiaSuratMasuk::select(array_map("trim", explode(",", $select)))
            ->orderBy("no", "desc")
            ->paginate(10, ["*"], "page", $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($data);
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
        if (!$request->hasFile('file')) {
            $request->request->remove('file');
        }

        $file      = $request->file("file");
        $file_name = null;
        $st        = new \Illuminate\Support\Facades\Storage();
        $request->merge([
            'status' => '1',
        ]);

        $request->validate(self::validationRule(false));

        if ($file) {
            $file_name = strtotime(now()) . '-' . str_replace([' ', '_'], '-', $file->getClientOriginalName());

            if (!$st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'))) {
                $st::disk('sftp')->makeDirectory(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'));
            }
        }

        $request->merge([
            'berkas' => $file_name ?? '',
        ]);

        if ($request->pelaksanaan == 'null') {
            $request->merge([
                'pelaksanaan' => null,
            ]);
        }

        if ($request->pelaksanaan_end == 'null') {
            $request->merge([
                'pelaksanaan_end' => null,
            ]);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $file, $file_name, $st) {
                \App\Models\RsiaSuratMasuk::create($request->all());
                
                if ($file) {
                    try {
                        $st::disk('sftp')->put(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $file_name, file_get_contents($file));
                        \App\Helpers\Logger\RSIALogger::berkas("UPLOADED", 'info', ['file_name' => $file_name, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                    } catch (\Exception $e) {
                        \App\Helpers\Logger\RSIALogger::berkas("UPLOAD FAILED", 'error', ['file_name' => $file_name, 'file_size' => $file->getSize(), 'data' => $request->all(), 'error' => $e->getMessage()]);
                        throw new \Exception('Failed to upload file');
                    }
                }
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("STORE DATA FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error('Failed to save data', 'store_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("STORED", 'info', ['data' => $request->all()]);
        return \App\Helpers\ApiResponse::success('Data saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RsiaSuratMasuk  $rsiaSuratMasuk
     * @return \Illuminate\Http\Response
     */
    public function show($no)
    {
        $select = request()->input("select", "*");

        $data = RsiaSuratMasuk::select(array_map('trim', explode(',', $select)))->where('no', $no)->first();
        if (!$data) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        return new \App\Http\Resources\Berkas\CompleteResource($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RsiaSuratMasuk  $rsiaSuratMasuk
     * @return \Illuminate\Http\Response
     */
    public function edit(RsiaSuratMasuk $rsiaSuratMasuk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RsiaSuratMasuk  $rsiaSuratMasuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $no)
    {
        if (!$request->hasFile('file')) {
            $request->request->remove('file');
        }

        $file      = $request->file("file");
        $file_name = null;
        $st        = new \Illuminate\Support\Facades\Storage();
        $request->validate(self::validationRule());

        $data = RsiaSuratMasuk::where('no', $no)->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        if ($file) {
            $file_name = strtotime(now()) . '-' . str_replace([' ', '_'], '-', $file->getClientOriginalName());

            if (!$st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'))) {
                $st::disk('sftp')->makeDirectory(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'));
            }
        }

        $oldData   = $data->toArray();
        $oldBerkas = $data->berkas;
        $request->merge([
            'berkas' => $file ? $file_name : $data->berkas,
        ]);

        if ($request->pelaksanaan == 'null') {
            $request->merge([
                'pelaksanaan' => null,
            ]);
        }

        if ($request->pelaksanaan_end == 'null') {
            $request->merge([
                'pelaksanaan_end' => null,
            ]);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $data, $file, $file_name, $st, $oldBerkas) {
                // Update data in the database
                $data->update($request->all());

                // Delete old file if it exists
                if ($file && $data && $oldBerkas != '' && $st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $oldBerkas)) {
                    try {
                        $st::disk('sftp')->delete(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $oldBerkas);
                        \App\Helpers\Logger\RSIALogger::berkas("DELETED", 'info', ['file_name' => $oldBerkas]);
                    } catch (\Exception $e) {
                        \App\Helpers\Logger\RSIALogger::berkas("FAILED TO DELETE OLD FILE", 'error', ['file_name' => $oldBerkas, 'error' => $e->getMessage()]);
                        throw new \Exception('Failed to delete old file');
                    }
                }

                // Upload new file if provided
                if ($file) {
                    try {
                        $st::disk('sftp')->put(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $file_name, file_get_contents($file));
                        \App\Helpers\Logger\RSIALogger::berkas("UPLOADED", 'info', ['file_name' => $file_name, 'file_size' => $file->getSize()]);
                    } catch (\Exception $e) {
                        \App\Helpers\Logger\RSIALogger::berkas("FAILED TO UPLOAD", 'error', ['file_name' => $file_name, 'file_size' => $file->getSize(), 'error' => $e->getMessage()]);
                        throw new \Exception('Failed to upload new file');
                    }
                }
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("UPDATE FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage(), 'old_data' => $oldData]);
            return \App\Helpers\ApiResponse::error('Failed to update data', 'update_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("UPDATED", 'info', ['data' => $request->all()]);
        return \App\Helpers\ApiResponse::success('Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RsiaSuratMasuk  $rsiaSuratMasuk
     * @return \Illuminate\Http\Response
     */
    public function destroy($no)
    {
        $data = RsiaSuratMasuk::where('no', $no)->first();
        if (!$data) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                // Delete the data
                $data->delete();
                
                // Delete associated file if it exists
                $st = new \Illuminate\Support\Facades\Storage();
                if ($data->berkas != '' && $st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $data->berkas)) {
                    $st::disk('sftp')->delete(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $data->berkas);
                    \App\Helpers\Logger\RSIALogger::berkas("DELETED", 'info', ['data' => $data]);
                }
            });
        } catch (\Exception $e) {
            // Log the failure to delete data and return an error response
            \App\Helpers\Logger\RSIALogger::berkas("FAILED TO DELETE DATA OR FILES", 'error', ['error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error('Failed to delete data', 'delete_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("DELETED", 'info', ['data' => $data]);
        return \App\Helpers\ApiResponse::success('Data deleted successfully');
    }

    private static function validationRule($withRequired = true)
    {
        return [
            "file"            => "file|mimes:pdf,jpg,jpeg,png|max:32768",

            "no_simrs"        => "required|date",
            "no_surat"        => "string|nullable",
            "pengirim"        => "required|string",
            "tgl_surat"       => "date|nullable",
            "perihal"         => "required|string",
            "pelaksanaan"     => "nullable",
            "pelaksanaan_end" => "nullable",
            "tempat"          => "string|nullable",
            "ket"             => "required|string|in:-,fisik,email,wa,larsi",
            "berkas"          => "string||nullable",
            "status"          => "string|in:0,1",
        ];
    }
}
