<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RsiaPenerimaUndanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 
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
            'no_surat' => 'required|string',
            'tipe'     => 'required|string|in:surat/internal,komite/ppi,komite/pmkp,komite/medis,komite/keperawatan,komite/kesehatan,berkas/notulen',
            'model'    => 'required|string',
        ]);

        // App\\\\Models\\\\RsiaSuratInternal to App\Models\RsiaSuratInternal
        $request->merge([
            'model' => str_replace('\\\\', '\\', $request->model),
        ]);

        // check if model file exists model from request is App\Models\RsiaSuratInternal
        if (!file_exists(app_path('Models/' . str_replace('App\Models\\', '', $request->model) . '.php'))) {
            \App\Helpers\Logger\RSIALogger::undangan("Model tidak ditemukan", "error", ['model' => $request->model]);
            return ApiResponse::error('Model ' . $request->model . ' not found -- ' . str_replace('App\Models\\', '', $request->model), 'resource_not_found', null, 404);
        }

        // check if no_surat is base64 encoded or not if base 64 decode it if not return as is
        $no_surat = base64_encode(base64_decode($request->no_surat) . '') === $request->no_surat ? base64_decode($request->no_surat) : $request->no_surat;

        // check if no_surat exists using the model
        $model = new $request->model;
        $surat = $model->where('no_surat', $no_surat)->first();

        if (!$surat) {
            return ApiResponse::error('Data not found', 'resource_not_found', null, 404);
        }

        $penerima = $request->penerima ?? [];

        // check if penerima is array
        if (!is_array($request->penerima)) {
            $penerima = explode(',', str_replace(['[', ']', '\''], '', $request->penerima));
        }

        // check if penerima is empty
        if (empty($penerima)) {
            return ApiResponse::error('Invalid data : Penerima undangan tidak boleh kosong', 'invalid_request', 400);
        }

        // validate penerima undangan is exists on pegawai table
        $pegawai         = \App\Models\Pegawai::whereIn('nik', $penerima)->get();
        $pegawaiNik      = $pegawai->pluck('nik')->toArray();
        $invalidPenerima = array_diff($penerima, $pegawaiNik);

        if (!empty($invalidPenerima)) {
            \App\Helpers\Logger\RSIALogger::undangan("INVALID RECIPIENT", "error", ['penerima' => $invalidPenerima]);
            return ApiResponse::error('Invalid data : Penerima undangan ' . implode(', ', $invalidPenerima) . ' tidak ditemukan', 'invalid_params', null, 400);
        }

        // inset penerima undangan to database
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $penerima, $no_surat) {
                \App\Models\RsiaPenerimaUndangan::where('no_surat', $no_surat)->delete();

                foreach ($penerima as $nik) {
                    \App\Models\RsiaPenerimaUndangan::updateOrCreate([
                        'no_surat' => $no_surat,
                        'penerima' => $nik,
                        'tipe'     => $request->tipe,
                        'model'    => $request->model,
                    ]);
                }
            });
        } catch (\Exception $e) {
            \App\Helpers\Logger\RSIALogger::undangan("ERROR SAVING RECIPIENT", "error", ['error' => $e->getMessage()]);
            return ApiResponse::error('Failed to store data', 'store_failed', $e->getMessage(), 500);
        }

        \App\Helpers\Logger\RSIALogger::undangan("RECIPIENT STORED", "info", ['model' => $request->model, 'no_surat' => $no_surat, 'penerima' => $penerima, 'tipe' => $request->tipe]);
        return ApiResponse::success('Data stored successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($encodedNoSurat)
    {
        $page = request()->get('page', 1);
        $decodedNoSurat = base64_decode($encodedNoSurat);
        
        // $penerimaUndangan = \App\Models\RsiaPenerimaUndangan::where('no_surat', $decodedNoSurat)->paginate(10, ['*'], 'page', $page);
        $penerimaUndangan = \App\Models\RsiaPenerimaUndangan::where('no_surat', $decodedNoSurat)->get();


        if ($penerimaUndangan->isEmpty()) {
            return ApiResponse::error('Data not found : Data penerima undangan tidak ditemukan', 'resource_not_found', null, 404);
        }

        return new \App\Http\Resources\Undangan\Penerima\CompleteCollection($penerimaUndangan);
        // return new \App\Http\Resources\Undangan\Penerima\RealCollection($penerimaUndangan);
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
    public function update(Request $request, $encodedNoSurat)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($encodedNoSurat)
    {
        // 
    }
}
