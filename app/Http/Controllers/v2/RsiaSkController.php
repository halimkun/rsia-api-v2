<?php

namespace App\Http\Controllers\v2;

use App\Models\RsiaSk;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RsiaSkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $data = RsiaSk::select(array_map('trim', explode(',', $select)))
            ->with(['penanggungJawab' => function ($query) {
                $query->select('nik', 'nama');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

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
        $request->validate([
            'jenis'      => 'required|string',
            'judul'      => 'required|string',
            'pj'         => 'required|exists:pegawai,nik',
            'tgl_terbit' => 'required|date',
            'file'       => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        $file = $request->file('file');
        $file_name = $file ? strtotime(now()) . '-' . str_replace(' ', '_', $file->getClientOriginalName()) : '';

        try {
            DB::transaction(function () use ($request, $file, $file_name) {
                $tglTerbit = \Carbon\Carbon::parse($request->tgl_terbit);
                $lastNomor = RsiaSk::whereYear('tgl_terbit', $tglTerbit->year)->where('jenis', $request->jenis)->max('nomor');

                if (!$lastNomor) {
                    $lastNomor = 0;
                }

                $newNomor = $lastNomor + 1;

                $request->merge([
                    'nomor'     => $newNomor,
                    'berkas'    => $file_name,
                ]);

                $data = RsiaSk::create($request->all());

                if ($file) {
                    $st = new Storage();

                    if (!$st::disk('sftp')->exists(env('DOCUMENT_SK_SAVE_LOCATION'))) {
                        \App\Helpers\Logger\RSIALogger::berkas("directory not found, creating directory : " . env('DOCUMENT_SK_SAVE_LOCATION'), 'info');
                        $st::disk('sftp')->makeDirectory(env('DOCUMENT_SK_SAVE_LOCATION'));
                    }

                    \App\Helpers\Logger\RSIALogger::berkas("file uploaded successfully", 'info', ['file_name' => $file_name, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                    $st::disk('sftp')->put(env('DOCUMENT_SK_SAVE_LOCATION') . $file_name, file_get_contents($file));
                }
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("STORE FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return ApiResponse::error('failed to save data', 'store_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("STORED", 'info', ['data' => $request->all()]);
        return ApiResponse::success('data saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        if (!base64_decode($identifier, true)) {
            return ApiResponse::error("Invalid parameter : Parameter tidak valid, pastikan parameter adalah base64 encoded dari nomor, jenis dan tanggal misal : 53.B.2024-03-28", "params_invalid", null, 400);
        }

        $decodedId = base64_decode($identifier);
        [$nomor, $jenis, $tgl_terbit] = explode('.', $decodedId);

        $data = RsiaSk::where('nomor', $nomor)
            ->where('jenis', $jenis)
            ->whereDate('tgl_terbit', $tgl_terbit)
            ->with(['penanggungJawab' => function ($query) {
                $query->select('nik', 'nama');
            }])->first();

        if (!$data) {
            return ApiResponse::error('data not found -- identifier : ' . $identifier, 'resource_not_found', 404);
        }

        return new \App\Http\Resources\Berkas\CompleteResource($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $identifier)
    {
        $request->validate([
            'jenis'      => 'required|string',
            'judul'      => 'required|string',
            'pj'         => 'required|string',
            'tgl_terbit' => 'required|date',
            'file'       => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        if (!base64_decode($identifier, true)) {
            return ApiResponse::error("Invalid parameter : Parameter tidak valid, pastikan parameter adalah base64 encoded dari nomor, jenis dan tanggal misal : 53.B.2024-03-28", "params_invalid", null, 400);
        }

        $decodedId = base64_decode($identifier);
        [$nomor, $jenis, $tgl_terbit] = explode('.', $decodedId);

        $data = RsiaSk::where('nomor', $nomor)
            ->where('jenis', $jenis)
            ->whereDate('tgl_terbit', $tgl_terbit)
            ->first();

        if (!$data) {
            return ApiResponse::error('data not found -- identifier : ' . $identifier, 'resource_not_found', 404);
        }

        $oldData   = $data->toArray();
        $oldFile   = $data->berkas;
        $file      = $request->file('file');
        $file_name = $file ? strtotime(now()) . '-' . str_replace(' ', '_', $file->getClientOriginalName()) : $data->berkas;

        $request->merge([
            'berkas' => $file_name,
        ]);

        // unset file from request
        $request->offsetUnset('file');

        try {
            DB::transaction(function () use ($request, $file, $file_name, $data, $oldFile) {
                $data->update($request->except(['created_at', 'penanggung_jawab', 'file']));

                if ($file) {
                    $st = new Storage();

                    if (!$st::disk('sftp')->exists(env('DOCUMENT_SK_SAVE_LOCATION'))) {
                        \App\Helpers\Logger\RSIALogger::berkas("directory not found, creating directory : " . env('DOCUMENT_SK_SAVE_LOCATION'), 'info');
                        $st::disk('sftp')->makeDirectory(env('DOCUMENT_SK_SAVE_LOCATION'));
                    }

                    $st::disk('sftp')->put(env('DOCUMENT_SK_SAVE_LOCATION') . $file_name, file_get_contents($file));

                    if ($oldFile && $oldFile != '' && $st::disk('sftp')->exists(env('DOCUMENT_SK_SAVE_LOCATION') . $oldFile)) {
                        \App\Helpers\Logger\RSIALogger::berkas("deleting old file", 'info', ['file_name' => $oldFile]);
                        $st::disk('sftp')->delete(env('DOCUMENT_SK_SAVE_LOCATION') . $oldFile);
                    }
                    
                    $data->update(['berkas' => $file_name]);
                }
                
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("STORE FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return ApiResponse::error('failed to save data', 'update_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("UPDATED", 'info', ['old_data' => $oldData, 'data' => $request->all()]);
        return ApiResponse::success('data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($identifier)
    {
        if (!base64_decode($identifier, true)) {
            return ApiResponse::error("Invalid parameter : Parameter tidak valid, pastikan parameter adalah base64 encoded dari nomor, jenis dan tanggal misal : 53.B.2024-03-28", "params_invalid", null, 400);
        }

        $decodedId = base64_decode($identifier);
        [$nomor, $jenis, $tgl_terbit] = explode('.', $decodedId);

        $data = RsiaSk::where('nomor', $nomor)
            ->where('jenis', $jenis)
            ->whereDate('tgl_terbit', $tgl_terbit)
            ->first();

        if (!$data) {
            return ApiResponse::error('data not found -- identifier : ' . $identifier, 'resource_not_found', 404);
        }

        try {
            DB::transaction(function () use ($data) {
               $data->delete();
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("DELETE FAILED", 'error', ['data' => $data, 'error' => $e->getMessage()]);
            return ApiResponse::error('failed to delete data', 'delete_failed', $e->getMessage(), 500);
        }
        
        $st = new Storage();
        if ($data->berkas && $data->berkas != '' && $st::disk('sftp')->exists(env('DOCUMENT_SK_SAVE_LOCATION') . $data->berkas)) {
            \App\Helpers\Logger\RSIALogger::berkas("deleting file", 'info', ['file_name' => $data->berkas]);
            $st::disk('sftp')->delete(env('DOCUMENT_SK_SAVE_LOCATION') . $data->berkas);
        }
        
        \App\Helpers\Logger\RSIALogger::berkas("DELETED", 'info', ['data' => $data]);
        return ApiResponse::success('data deleted successfully');
    }
}
