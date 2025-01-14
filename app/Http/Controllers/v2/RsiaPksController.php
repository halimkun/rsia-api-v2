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
            ->with('penanggungJawab')
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

        if ($lastNomor) {
            $explodedLastNomor = explode('/', $lastNomor->no_pks_internal);
    
            $buildedNomor = [
                str_pad(($explodedLastNomor[0] + 1), 3, '0', STR_PAD_LEFT),
                $request->jenis,
                'PKS-RSIA',
                \Carbon\Carbon::parse($request->tgl_terbit)->format('dmy'),
            ];
        } else {
            $buildedNomor = [
                '001',
                $request->jenis,
                'PKS-RSIA',
                \Carbon\Carbon::parse($request->tgl_terbit)->format('dmy'),
            ];
        }

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
                if (!$st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION'))) {
                    \App\Helpers\Logger\RSIALogger::berkas("DIRECTORY NOT FOUND :: CREATING : " . env('DOCUMENT_PKS_SAVE_LOCATION'), 'info');
                    $st::disk('sftp')->makeDirectory(env('DOCUMENT_PKS_SAVE_LOCATION'));
                }

                \App\Helpers\Logger\RSIALogger::berkas("UPLOAD SUCCESS", 'info', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                $st::disk('sftp')->put(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName, file_get_contents($file));
            }
        } catch (\Exception $e) {
            if ($request->hasFile('file')) {
                if ($file && $fileName != '' && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName)) {
                    \App\Helpers\Logger\RSIALogger::berkas("STORE OR UPLOAD FAILED :: DELETING ", 'error', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                    $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName);
                }
            }

            \App\Helpers\Logger\RSIALogger::berkas("STORE FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error("failed to save data", "store_failed", $e->getMessage(), 500);
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
            ->with('penanggungJawab')
            ->first();

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "resource_not_found", null, 404);
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
            'no_pks_eksternal' => 'nullable|string',
            'judul'            => 'required|string',
            'tgl_terbit'       => 'required|date',
            'tanggal_awal'     => 'date',
            'pj'               => 'required|string|exists:pegawai,nik',
            // 'jenis'            => 'required|string|in:A,B',
            'file'             => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        $data = \App\Models\RsiaPks::find($id);

        if (!$data) {
            return \App\Helpers\ApiResponse::error("Resource not found", "resource_not_found", null, 404);
        }

        $oldData  = $data->toArray();
        $file     = $request->file('file');
        $oldFIle  = $data->berkas;
        $fileName = $file ? strtotime(now()) . '-' . str_replace(' ', '_', $file->getClientOriginalName()) : $oldFIle;

        try {
            DB::transaction(function () use ($request, $data, $file, $fileName, $oldFIle) {

                if ($request->tanggal_akhir == 'null') {
                    $request->merge(['tanggal_akhir' => null]);
                }

                // check no_pks_eksternal on request if null replare with empty strings
                if ($request->no_pks_eksternal == 'null' || $request->no_pks_eksternal == null) {
                    $request->merge(['no_pks_eksternal' => '']);
                }

                // check tanggal_awal on request if null replare with empty 0000-00-00
                if ($request->tanggal_akhir == 'null' || $request->tanggal_akhir == null) {
                    $request->merge(['tanggal_akhir' => '0000-00-00']);
                }

                $data->update($request->except(['file', 'berkas']));

                if ($file) {
                    $st = new \Illuminate\Support\Facades\Storage();
                    // if directory not exists create it
                    if (!$st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION'))) {
                        \App\Helpers\Logger\RSIALogger::berkas("DIRECTORY NOT FOUND :: CREATING : " . env('DOCUMENT_PKS_SAVE_LOCATION'), 'info');
                        $st::disk('sftp')->makeDirectory(env('DOCUMENT_PKS_SAVE_LOCATION'));
                    }

                    $st::disk('sftp')->put(env('DOCUMENT_PKS_SAVE_LOCATION') . $fileName, file_get_contents($file));

                    $data->update(['berkas' => $fileName]);

                    if ($request->hasFile('file')) {
                        $st = new \Illuminate\Support\Facades\Storage();
                        if ($oldFIle && $oldFIle != '' && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle)) {
                            \App\Helpers\Logger\RSIALogger::berkas("UPLOAD SUCCESS :: DELETING OLD FILE ", 'info', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                            $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            if ($request->hasFile('file')) {
                $st = new \Illuminate\Support\Facades\Storage();
                if ($oldFIle && $oldFIle != '' && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle)) {
                    \App\Helpers\Logger\RSIALogger::berkas("UPDATE OR UPLOAD FAILED :: DELETING FILE", 'error', ['file_name' => $fileName, 'file_size' => $file->getSize(), 'data' => $request->all()]);
                    $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $oldFIle);
                }
            }

            \App\Helpers\Logger\RSIALogger::berkas("UPDATE FAILED", 'error', ['data' => $request->all(), 'error' => $e->getMessage(), 'old_data' => $oldData]);
            return \App\Helpers\ApiResponse::error("failed to update data", "updated_failed", $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::berkas("UPDATED", 'info', ['data' => $request->all(), 'old_data' => $oldData]);
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
            return \App\Helpers\ApiResponse::error("Resource not found", "resource_not_found", null, 404);
        }

        try {
            DB::transaction(function () use ($data) {
                $data->delete();
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::berkas("DELETE FAILED", 'error', ['data' => $data, 'error' => $e->getMessage()]);
            return \App\Helpers\ApiResponse::error("failed to delete data", "delete_failed", $e->getMessage(), 500);
        }

        $st = new \Illuminate\Support\Facades\Storage();
        if ($data->berkas && $data->berkas != '' && $st::disk('sftp')->exists(env('DOCUMENT_PKS_SAVE_LOCATION') . $data->berkas)) {
            \App\Helpers\Logger\RSIALogger::berkas("DELETING FILE", 'info', ['file_name' => $data->berkas]);
            $st::disk('sftp')->delete(env('DOCUMENT_PKS_SAVE_LOCATION') . $data->berkas);
        }

        \App\Helpers\Logger\RSIALogger::berkas("DELETED", 'info', ['data' => $data]);
        return \App\Helpers\ApiResponse::success("data deleted successfully");
    }
}
