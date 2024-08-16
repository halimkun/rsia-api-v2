<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\RsiaCuti;
use Illuminate\Http\Request;

class CutiPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(string $nik)
    {
        $cuti = RsiaCuti::where('nik', $nik)->orderBy('tanggal_cuti', 'desc')->paginate(10);
        return new \App\Http\Resources\RealDataCollection($cuti);
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
    public function store(string $nik, Request $request)
    {
        $request->validate([
            // tanggal_cuti is object of start and end
            'tanggal_cuti' => 'required',
            'jenis'        => 'required|string|in:Cuti Tahunan,Cuti Bersalin,Cuti Diluar Tanggungan,Cuti Besar'
        ]);

        if (is_array($request->tanggal_cuti)) {
            $request->validate([
                'tanggal_cuti.start' => 'required|date',
                'tanggal_cuti.end'   => 'required|date'
            ]);
        }

        $pegawai = \App\Models\Pegawai::where('nik', $nik)->first();

        if (!$pegawai) {
            return ApiResponse::error('Pegawai tidak ditemukan', "resource_not_found", null, 404);
        }

        $data = [
            'id_pegawai'        => $pegawai->id_pegawai,
            'nik'               => $nik,
            'nama'              => $pegawai->nama,
            'dep_id'            => $pegawai->departemen,
            'tanggal_cuti'      => $request->tanggal_cuti['start'],
            'id_jenis'          => $this->getIdJenisCuti($request->jenis),
            'jenis'             => $request->jenis,
            'status'            => 0,
            'tanggal_pengajuan' => \Carbon\Carbon::now()
        ];

        $dataCutiBersalin = [];
        if ($request->jenis == "Cuti Bersalin") {
            $request->validate([
                'tanggal_selesai' => 'required|date'
            ]);

            $dataCutiBersalin = [
                'tgl_mulai'   => $request->tanggal_cuti['start'],
                'tgl_selesai' => $request->tanggal_cuti['end'],
            ];
        }

        try {
            \DB::transaction(function () use ($data, $dataCutiBersalin) {
                $cuti = RsiaCuti::create($data);

                if ($cuti->jenis == "Cuti Bersalin") {
                    $dataCutiBersalin['id_cuti'] = $cuti->id_cuti;

                    \App\Models\RsiaCutiBersalin::create($dataCutiBersalin);
                }
            }, 5);

            return ApiResponse::success('Cuti berhasil diajukan');
        } catch (\Throwable $th) {
            return ApiResponse::error('Cuti gagal diajukan', "internal_server_error", 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $nik, int $id)
    {
        $cuti = RsiaCuti::where('nik', $nik)->where('id_cuti', $id)->first();
        return new \App\Http\Resources\RealDataResource($cuti);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $nik, int $id)
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
    public function destroy(string $nik, int $id)
    {
        $cuti = RsiaCuti::where('nik', $nik)->where('id_cuti', $id)->first();

        if ($cuti->status == 0) {
            \DB::transaction(function () use ($cuti) {
                $cuti->delete();

                // check cuti bersalin if exists then delete
                $cutiBersalin = \App\Models\RsiaCutiBersalin::where('id_cuti', $cuti->id_cuti)->first();
                if ($cutiBersalin) {
                    $cutiBersalin->delete();
                }
            }, 5);
        } else {
            return ApiResponse::error('Cuti sudah disetujui, tidak bisa dihapus', "resource_not_found", 404);
        }

        return ApiResponse::success('Cuti berhasil dihapus');
    }

    /**
     * Counter cuti pegawai
     * 
     * @param string $nik
     * @return \App\Http\Resources\RealDataResource
     * */
    public function counterCuti(string $nik)
    {
        $hitung = \Illuminate\Support\Facades\DB::table('pegawai as t1')
            ->select(\Illuminate\Support\Facades\DB::raw("(SELECT count(id_pegawai) from rsia_cuti WHERE id_pegawai=t1.id and id_jenis = '1' and YEAR(tanggal_cuti)=year(curdate()) and MONTH(tanggal_cuti) < 07 and status_cuti='2' ) as jml1, (SELECT count(id_pegawai) from rsia_cuti WHERE id_pegawai=t1.id and id_jenis = '1' and MONTH(tanggal_cuti) > 06 and YEAR(tanggal_cuti)=year(curdate()) and MONTH(tanggal_cuti) <= 12 and status_cuti='2') as jml2"))
            ->where('t1.nik', $nik)
            ->get();

        $hitung = collect($hitung->first());

        return new \App\Http\Resources\RealDataResource($hitung);
    }

    /**
     * Get id jenis cuti
     * 
     * @param string $jenis
     * @return int
     * */
    private function getIdJenisCuti(string $jenis)
    {
        $jenisCuti = [
            'Cuti Tahunan'          => 1,
            'Cuti Bersalin'         => 2,
            'Cuti Diluar Tanggungan' => 3,
            'Cuti Besar'            => 4
        ];

        return $jenisCuti[$jenis];
    }
}
