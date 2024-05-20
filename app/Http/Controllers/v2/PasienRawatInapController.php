<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasienRawatInapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = \App\Models\KamarInap::where('tgl_keluar', '0000-00-00')
            ->where('stts_pulang', '-')
            ->with(['regPeriksa' => function ($q) {
                $q->select("no_rawat", "no_rkm_medis", "tgl_registrasi", "jam_reg", "p_jawab", "hubunganpj")
                    ->with(['pasien' => function ($q) {
                        $q->select('no_rkm_medis', 'nm_pasien', 'jk', 'tmp_lahir', 'tgl_lahir');
                    }]);
            }])
            ->paginate(10);

        return new \App\Http\Resources\Pasien\Ranap\CompleteCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return ApiResponse::error("unimplemented", 501);
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
        return ApiResponse::error("unimplemented", 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ApiResponse::error("unimplemented", 501);
    }
}
