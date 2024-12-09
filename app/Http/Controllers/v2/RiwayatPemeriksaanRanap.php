<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPemeriksaanRanap extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($rm, $noRawat, Request $request)
    {
        // check pasien exists
        if (!\App\Models\Pasien::where('no_rkm_medis', $rm)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Pasien dengan no_rkm_medis: $rm tidak ditemukan");
        }

        try {
            $noRawat = base64_decode($noRawat);
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        if (!\App\Models\RegPeriksa::where('no_rawat', $noRawat)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        if ($request->has('interval') && $request->interval) {
            $currentData = \App\Models\RegPeriksa::where('no_rawat', $noRawat)->first();
            $add30Days = date('Y-m-d', strtotime($currentData->tgl_registrasi . ' +30 days'));
            $sub30Days = date('Y-m-d', strtotime($currentData->tgl_registrasi . ' -30 days'));

            $realData = \App\Models\RegPeriksa::where('no_rkm_medis', $rm)
                ->whereBetween('tgl_registrasi', [$sub30Days, $add30Days])
                ->orderBy('tgl_registrasi', 'desc')->paginate(10);

            $riwayat = \App\Models\PemeriksaanRanap::with(['petugas', 'regPeriksa', 'pemeriksaanKlaim'])->whereIn('no_rawat', $realData->pluck('no_rawat')->toArray())
                ->orderBy('no_rawat', 'desc')
                ->orderBy('tgl_perawatan', 'desc')
                ->orderBy('jam_rawat', 'desc')
                ->paginate(10);

            return new \App\Http\Resources\RealDataCollection($riwayat);
        } else {
            $riwayat = \App\Models\PemeriksaanRanap::with('petugas')->where('no_rawat', $noRawat)
                ->orderBy('no_rawat', 'desc')
                ->orderBy('tgl_perawatan', 'desc')
                ->orderBy('jam_rawat', 'desc')
                ->paginate(10);

            return new \App\Http\Resources\RealDataCollection($riwayat);
        }
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

    public function syncKlaim(Request $request)
    {
        // validate 3 items, (no_rawat, tgl_perawatan, jam_rawat)
        $request->validate([
            'no_rawat'      => 'required',
            'tgl_perawatan' => 'required|date',
            'jam_rawat'     => 'required',
        ]);

        // find data by no_rawat, tgl_perawatan, jam_rawat in pemeriksaan_ranap
        $pemeriksaanRanap = \App\Models\PemeriksaanRanap::where('no_rawat', $request->no_rawat)
            ->where('tgl_perawatan', $request->tgl_perawatan)
            ->where('jam_rawat', $request->jam_rawat)
            ->first();

        // update or create data in pemeriksaan klaim
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $pemeriksaanRanap) {
                \App\Models\PemeriksaanRanapKlaim::updateOrCreate([
                    'no_rawat'      => $request->no_rawat,
                    'tgl_perawatan' => $request->tgl_perawatan,
                    'jam_rawat'     => $request->jam_rawat,
                ], $pemeriksaanRanap->toArray());
            }, 5);

            return \App\Helpers\ApiResponse::success('Data pemeriksaan klaim berhasil disinkronkan');
        } catch (\Throwable $th) {
            return \App\Helpers\ApiResponse::error($th->getMessage(), 'unable to sync data', 500);
        }
    }

    /**
     * Delete synced data from pemeriksaan klaim
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \App\Helpers\ApiResponse
     * */
    public function deleteSyncedData(Request $request)
    {
        // validate 3 items, (no_rawat, tgl_perawatan, jam_rawat)
        $request->validate([
            'no_rawat'      => 'required',
            'tgl_perawatan' => 'required|date',
            'jam_rawat'     => 'required',
        ]);

        // find data by no_rawat, tgl_perawatan, jam_rawat in pemeriksaan_ranap
        $pemeriksaanRanapKlaim = \App\Models\PemeriksaanRanapKlaim::where('no_rawat', $request->no_rawat)
            ->where('tgl_perawatan', $request->tgl_perawatan)
            ->where('jam_rawat', $request->jam_rawat)
            ->first();

        if (!$pemeriksaanRanapKlaim) {
            return \App\Helpers\ApiResponse::notFound('Data pemeriksaan klaim tidak ditemukan');
        }

        // update or create data in pemeriksaan klaim
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $pemeriksaanRanapKlaim) {
                $pemeriksaanRanapKlaim->delete();
            }, 5);

            return \App\Helpers\ApiResponse::success('Data pemeriksaan klaim berhasil dihapus');
        } catch (\Throwable $th) {
            return \App\Helpers\ApiResponse::error($th->getMessage(), 'unable to delete data', 500);
        }
    }

    /**
     * Get tensi from riwayat pemeriksaan ranap
     * 
     * @param string $rm
     * @param string $noRawat
     * 
     * @return \App\Helpers\ApiResponse
     * */
    public function getTensi($rm, $noRawat)
    {
        // check pasien exists
        if (!\App\Models\Pasien::where('no_rkm_medis', $rm)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Pasien dengan no_rkm_medis: $rm tidak ditemukan");
        }

        try {
            $noRawat = base64_decode($noRawat);
        } catch (\Exception $e) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        if (!\App\Models\RegPeriksa::where('no_rawat', $noRawat)->exists()) {
            return \App\Helpers\ApiResponse::notFound("Riwayat pemeriksaan dengan no_rawat: $noRawat tidak ditemukan");
        }

        $riwayat = \App\Models\PemeriksaanRanap::select('tensi')->where('no_rawat', $noRawat)
            ->where('tensi', '!=', '')->where('tensi', '!=', '-')->where('tensi', '!=', null)
            ->orderBy('no_rawat', 'desc')->orderBy('tgl_perawatan', 'desc')->orderBy('jam_rawat', 'desc')
            ->first();

        return new \App\Http\Resources\RealDataResource($riwayat);
    }
}
