<?php

namespace App\Http\Controllers\v2;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\RsiaFileManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RsiaFileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = RsiaFileManager::paginate(25);
        return new \App\Http\Resources\RealDataCollection($data);
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
            'file'      => 'required|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png',
            'nama_file' => 'required',
        ]);

        $st = new Storage();
        $file = $request->file('file');

        if (!$file) {
            \Log::error('FILEMANAGER - File not found');
            return ApiResponse::error('Request not valid', 400);
        }

        $fileName = Str::slug($request->nama_file) . '-' . time() . '.' . $file->getClientOriginalExtension();

        // save data to database using transaction
        \DB::beginTransaction();
        try {
            $data = RsiaFileManager::create([
                'nama_file' => $request->nama_file,
                'file'      => $fileName,
            ]);

            if (!Storage::disk('sftp')->exists(env('RSIAP_BERKAS_SAVE_LOCATION'))) {
                Storage::disk('sftp')->makeDirectory(env('RSIAP_BERKAS_SAVE_LOCATION'));
            }

            Storage::disk('sftp')->put(env('RSIAP_BERKAS_SAVE_LOCATION') . $fileName, file_get_contents($file));
            
            \DB::commit();
            return ApiResponse::successWithData($data, 'Data berhasil disimpan');
        } catch (\Exception $e) {

            if ($fileName != '' && Storage::disk('sftp')->exists(env('RSIAP_BERKAS_SAVE_LOCATION') . $fileName)) {
                Storage::disk('sftp')->delete(env('RSIAP_BERKAS_SAVE_LOCATION') . $fileName);
            }

            \DB::rollBack();
            \Log::error('FILEMANAGER - ' . $e->getMessage());
            return ApiResponse::error('Data gagal disimpan', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RsiaFileManager  $rsiaFileManager
     * @param  \App\Models\RsiaFileManager  $rsiaFileManager
     * @return \Illuminate\Http\Response
     */
    public function show(RsiaFileManager $rsiaFileManager, $id)
    {
        $data = RsiaFileManager::find($id);
        if (!$data) {
            return ApiResponse::notFound('Data tidak ditemukan');
        }

        return new \App\Http\Resources\RealDataResource($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RsiaFileManager  $rsiaFileManager
     * @return \Illuminate\Http\Response
     */
    public function edit(RsiaFileManager $rsiaFileManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RsiaFileManager  $rsiaFileManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RsiaFileManager $rsiaFileManager, $id)
    {
        $rsiaFileManager = RsiaFileManager::find($id);
        $request->validate([
            'file'      => 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png',
            'nama_file' => 'required',
        ]);

        $file = $request->file('file');

        if ($file) {
            $fileName = Str::slug($request->nama_file) . '-' . time() . '.' . $file->getClientOriginalExtension();
        } else {
            $fileName = $rsiaFileManager->file;
        }

        // save data to database using transaction
        \DB::beginTransaction();
        try {
            $rsiaFileManager->update([
                'nama_file' => $request->nama_file,
                'file'      => $fileName,
            ]);

            if ($file) {
                if (!Storage::disk('sftp')->exists(env('RSIAP_BERKAS_SAVE_LOCATION'))) {
                    Storage::disk('sftp')->makeDirectory(env('RSIAP_BERKAS_SAVE_LOCATION'));
                }

                Storage::disk('sftp')->put(env('RSIAP_BERKAS_SAVE_LOCATION') . $fileName, file_get_contents($file));
            }

            \DB::commit();
            return ApiResponse::successWithData($rsiaFileManager, 'Data berhasil diupdate');
        } catch (\Exception $e) {

            if ($file && $fileName != '' && Storage::disk('sftp')->exists(env('RSIAP_BERKAS_SAVE_LOCATION') . $fileName)) {
                Storage::disk('sftp')->delete(env('RSIAP_BERKAS_SAVE_LOCATION') . $fileName);
            }

            \DB::rollBack();
            \Log::error('FILEMANAGER - ' . $e->getMessage());
            return ApiResponse::error('Data gagal diupdate', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RsiaFileManager  $rsiaFileManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(RsiaFileManager $rsiaFileManager, $id)
    {
        $rsiaFileManager = RsiaFileManager::find($id);
        \DB::beginTransaction();
        try {
            $rsiaFileManager->delete();
            // $rsiaFileManager->forceDelete();

            if ($rsiaFileManager->file && Storage::disk('sftp')->exists(env('RSIAP_BERKAS_SAVE_LOCATION') . $rsiaFileManager->file)) {
                Storage::disk('sftp')->delete(env('RSIAP_BERKAS_SAVE_LOCATION') . $rsiaFileManager->file);
            }

            \DB::commit();
            return ApiResponse::success('Data berhasil dihapus');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('FILEMANAGER - ' . $e->getMessage());
            return ApiResponse::error('Data gagal dihapus', 500);
        }
    }
}
