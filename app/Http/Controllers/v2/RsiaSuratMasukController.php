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
        $file = $request->file("file");
        $request->merge([
            'status' => '1',
        ]);

        $request->validate(self::validationRule(false));

        if ($file) {
            $file_name = strtotime(now()) . '-' . str_replace([' ', '_'], '-', $file->getClientOriginalName());

            $st = new \Illuminate\Support\Facades\Storage();
            if (!$st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'))) {
                $st::disk('sftp')->makeDirectory(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'));
            }
        }

        $request->merge([
            'berkas' => $file_name ?? '',
        ]);

        try {
            \DB::transaction(function () use ($request) {
                \App\Models\RsiaSuratMasuk::create($request->all());
            });
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Failed to save data', $e->getMessage(), 500);
        }

        if ($file) {
            $st::disk('sftp')->put(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $file_name, file_get_contents($file));
        }

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

    public function search(Request $request)
    {
        $page = $request->input('page', 1);
        $select = $request->input('select', '*');

        $orion_request = new OrionRequest($request->all());
        $actionMethod = $request->route()->getActionMethod();

        $fd = new \App\Services\GetFilterData(new \App\Models\RsiaSuratMasuk(), $orion_request, $actionMethod);
        $query = $fd->apply();

        $data = $query->select(array_map('trim', explode(',', $select)))
            ->paginate(10, array_map('trim', explode(',', $select)), 'page', $page);

        return new \App\Http\Resources\Berkas\CompleteCollection($data);
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
        $file = $request->file("file");
        $request->validate(self::validationRule());

        $data = RsiaSuratMasuk::where('no', $no)->first();
        if (!$data) {
            return \App\Helpers\ApiResponse::notFound('Resource not found');
        }

        if ($file) {
            $file_name = strtotime(now()) . '-' . str_replace([' ', '_'], '-', $file->getClientOriginalName());

            $st = new \Illuminate\Support\Facades\Storage();
            if (!$st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'))) {
                $st::disk('sftp')->makeDirectory(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION'));
            }
        }

        $oldBerkas = $data->berkas;
        $request->merge([
            'berkas' => $file ? $file_name : $data->berkas,
        ]);

        try {
            \DB::transaction(function () use ($request, $data) {
                $data->update($request->all());
            });
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Failed to update data', $e->getMessage(), 500);
        }

        if ($file && $data && $st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $oldBerkas)) {
            $st::disk('sftp')->delete(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $oldBerkas);
        }

        // if file uoloaded, save new file
        if ($file) {
            $st::disk('sftp')->put(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $file_name, file_get_contents($file));
        }

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
            \DB::transaction(function () use ($data) {
                $data->delete();
            });
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Failed to delete data', $e->getMessage(), 500);
        }

        $st = new \Illuminate\Support\Facades\Storage();
        if ($data && $st::disk('sftp')->exists(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $data->berkas)) {
            $st::disk('sftp')->delete(env('DOCUMENT_SURAT_MASUK_SAVE_LOCATION') . $data->berkas);
        }

        return \App\Helpers\ApiResponse::success('Data deleted successfully');
    }

    private static function validationRule($withRequired = true)
    {
        return [
            "file"            => "file|mimes:pdf,jpg,jpeg,png|max:28672",

            "no_simrs"        => "required|date",
            "no_surat"        => "string|nullable",
            "pengirim"        => "required|string",
            "tgl_surat"       => "date|nullable",
            "perihal"         => "required|string",
            "pelaksanaan"     => "date|nullable",
            "pelaksanaan_end" => "date|nullable",
            "tempat"          => "string|nullable",
            "ket"             => "required|string|in:-,fisik,email,wa,larsi",
            "berkas"          => "string||nullable",
            "status"          => "required|string|in:0,1",
        ];
    }
}