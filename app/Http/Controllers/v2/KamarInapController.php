<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KamarInapController extends Controller
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
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        try {
            $key = base64_encode(base64_decode($key)) === $key ? base64_decode($key) : $key;
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::error('Invalid key', 'Key must be a valid base64 string');
        }

        $kamarInap = \App\Models\KamarInap::where('no_rawat', $key)
            ->orderBy('tgl_masuk', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();

        if (!$kamarInap) {
            return \App\Helpers\ApiResponse::notFound('Data pasien tidak ditemukan, periksa kembali nomor rawat');
        }

        return new \App\Http\Resources\Kamar\Inap\ShowKamarInapCollection($kamarInap);
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
}
