<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasienGradeIIIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'tgl_awal'      => 'required|date_format:Y-m-d',
            'tgl_akhir'     => 'required|date_format:Y-m-d',
            'jenis_tanggal' => 'required|string|in:tglsep,reg_periksa.tgl_registrasi,kamar_inap.tgl_keluar',
        ]);

        $pasiens = \App\Models\BridgingSep::select('no_sep', 'no_rawat', 'nomr', 'tglsep')
            ->with('pasien', 'groupStage', 'kamar_inap', 'reg_periksa', 'diagnosa.penyakit', 'prosedur.penyakit')
            ->whereHas('groupStage', function ($query) {
                $query->where('code_cbg', 'like', '%-III');
            })
            ->whereHas('kamar_inap', function ($query) use ($request) {
                $query->whereBetween($request->jenis_tanggal, [$request->tgl_awal, $request->tgl_akhir]);
            })
            ->get();

        // dd($pasiens->toArray());

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.pasien.grade-3', [
            'pasiens' => $pasiens,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
        ])->setPaper(
            [0, 0, 609.448, 935.432],
            'landscape'
        );

        return $pdf->stream('pasien-grade-3.pdf');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
