<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\RsiaNotulen;
use App\Models\RsiaPenerimaUndangan;
use Illuminate\Http\Request;

class RsiaUndanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $base64_no_surat
     * @return \Illuminate\Http\Response
     */
    public function show($base64_no_surat)
    {
        try {
            $no_surat = base64_decode($base64_no_surat);
            $undangan = RsiaPenerimaUndangan::where('no_surat', $no_surat)->first();

            if (!$undangan) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $model = new $undangan->model;
            $undangan = $model->with('penerima.kehadiran')->find($no_surat);
            
            return new \App\Http\Resources\RealDataResource($undangan);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get notulen from surat
     *
     * @param  string $base64_no_surat
     * @return \Illuminate\Http\Response
     */
    public function notulen($base64_no_surat)
    {
        try {
            $no_surat = base64_decode($base64_no_surat);
            $undangan = RsiaPenerimaUndangan::where('no_surat', $no_surat)->first();

            if (!$undangan) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $model = new $undangan->model;
            $undangan = $model->find($no_surat);

            if (!$undangan) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $notulen = RsiaNotulen::where('no_surat', $no_surat)->with('notulis')->first();

            $undangan->notulen = $notulen;
            
            return new \App\Http\Resources\RealDataResource($undangan);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
