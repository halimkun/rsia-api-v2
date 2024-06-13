<?php

namespace App\Http\Controllers\v2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

// Add Logging to all method

class RsiaPksController extends Controller
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

        $data = \App\Models\RsiaPks::select(array_map('trim', explode(',', $select)))
            ->with('penanggungjawab')
            ->where('status', 1)
            ->orderBy('tgl_terbit', 'desc')
            ->orderBy('id', 'desc')
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
            // 'no_pks_internal'  => 'required|string|min:20',
            'no_pks_eksternal' => 'string',
            'judul'            => 'required|string',
            'tgl_terbit'       => 'required|date',
            'tanggal_awal'     => 'date',
            'berkas'           => 'string',
            'pj'               => 'required|string|exists:pegawai,nik',
            'jenis'            => 'required|string|in:A,B',
            'file'             => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        $file = $request->file('file');
        $fileName = $file ? strtotime(now()) . '-' . str_replace(' ', '_', $file->getClientOriginalName()) : '';

        $lastNomor = \App\Models\RsiaPks::select('no_pks_internal')
            ->whereYear('tgl_terbit', date('Y', strtotime($request->tgl_terbit)))
            ->orderBy('no_pks_internal', 'desc')
            ->where('no_pks_internal', 'like', '%/' . $request->jenis . '/%')
            ->first();

        $explodedLastNomor = explode('/', $lastNomor->no_pks_internal);

        $buildedNomor = [
            ($explodedLastNomor[0] + 1),
            $request->jenis,
            'PKS-RSIA',
            \Carbon\Carbon::parse($request->tgl_terbit)->format('dmy'),
        ];

        $request->merge([
            'no_pks_internal' => implode('/', $buildedNomor),
            'berkas'          => $fileName,
        ]);

        try {
            DB::transaction(function () use ($request) {
                \App\Models\RsiaPks::create($request->all());
            });

            if ($file) {
                $st = new \Illuminate\Support\Facades\Storage();
                // if directory not exists create it
                if (!$st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION'))) {
                    \App\Helpers\Logger\RSIALogger::berkas("directory not found, creating directory : " . env('DOCUMENT_PKS_SAVE_LOCATION'), 'info');
                    $st::disk('sftp')->makeDirectory(env('DOCUMENT_PKS_SAVE_LOCATION'));
                }

                \App\Helpers\Logger\RSIALogger::berkas("file uploaded successfully", 'info', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                $st::disk('sftp')->put(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName, file_get_contents($file));
            }
        } catch (\Exception $e) {
            if ($request->hasFile('file')) {
                if ($file && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName)) {
                    \App\Helpers\Logger\RSIALogger::berkas("store data failed or file uploaded failed, deleting file", 'error', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                    $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName);
                }
            }

            \App\Helpers\Logger\RSIALogger::berkas("failed to save data", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error("failed to save data", $e->getMessage(), 500);
        }

        return \App\Helpers\ApiResponse::success("data saved successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = \App\Models\RsiaPks::where('id', $id)
            ->with('penanggungjawab')
            ->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "Data dengan detail tersebut tidak ditemukan", 404);
        }

        return new \App\Http\Resources\Berkas\Komite\CompleteResource($data);
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
    public function update(Request $request, $id)
    {
        $request->validate([
            // 'no_pks_internal'  => 'required|string|min:20',
            'no_pks_eksternal' => 'string',
            'judul'            => 'required|string',
            'tgl_terbit'       => 'required|date',
            'tanggal_awal'     => 'date',
            'pj'               => 'required|string|exists:pegawai,nik',
            // 'jenis'            => 'required|string|in:A,B',
            'file'             => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        $data = \App\Models\RsiaPks::find($id);

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "Data dengan detail tersebut tidak ditemukan", 404);
        }

        $file = $request->file('file');
        $oldFIle = $data->berkas;
        $fileName = $file ? strtotime(now()) . '-' . str_replace(' ', '_', $file->getClientOriginalName()) : $oldFIle;

        try {
            DB::transaction(function () use ($request, $data, $file, $fileName, $oldFIle) {

                if ($request->tanggal_akhir == 'null') {
                    $request->merge(['tanggal_akhir' => null]);
                }

                $data->update($request->except(['file', 'berkas']));

                if ($file) {
                    $st = new \Illuminate\Support\Facades\Storage();
                    // if directory not exists create it
                    if (!$st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION'))) {
                        \App\Helpers\Logger\RSIALogger::berkas("directory not found, creating directory : " . env('DOCUMENT_PKS_SAVE_LOCATION'), 'info');
                        $st::disk('sftp')->makeDirectory(env('DOCUMENT_PKS_SAVE_LOCATION'));
                    }

                    $st::disk('sftp')->put(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName, file_get_contents($file));

                    $data->update(['berkas' => $fileName]);

                    if ($request->hasFile('file')) {
                        $st = new \Illuminate\Support\Facades\Storage();
                        if ($oldFIle && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle)) {
                            \App\Helpers\Logger\RSIALogger::berkas("file uploaded successfully | deleting old file", 'info', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                            $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            if ($request->hasFile('file')) {
                $st = new \Illuminate\Support\Facades\Storage();
                if ($oldFIle && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle)) {
                    \App\Helpers\Logger\RSIALogger::berkas("failed to update data or file uploaded failed, deleting file", 'error', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                    $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle);
                }
            }

            \App\Helpers\Logger\RSIALogger::berkas("failed to update data", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error("failed to update data", $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("data updated successfully", 'info', ['data' => $request->all()]);
        return \App\Helpers\ApiResponse::success("data updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = \App\Models\RsiaPks::find($id);

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "Data dengan detail tersebut tidak ditemukan", 404);
        }

        try {
            DB::transaction(function () use ($data) {
                $data->delete();
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("failed to delete data", 'error', ['data' => $data, 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error("failed to delete data", $e->getMessage(), 500);
        }

        $st = new \Illuminate\Support\Facades\Storage();
        if ($data->berkas && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $data->berkas)) {
            \App\Helpers\Logger\RSIALogger::berkas("deleting file", 'info', ['file_name' => $data->berkas]);
            $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $data->berkas);
        }

        \App\Helpers\Logger\RSIALogger::berkas("data deleted successfully", 'info', ['data' => $data]);
        return \App\Helpers\ApiResponse::success("data deleted successfully");
    }
}
