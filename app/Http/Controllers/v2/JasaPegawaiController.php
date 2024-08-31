<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JasaPegawaiController extends Controller
{
    public function jm($nik, Request $request)
    {
        $jasaMedis = \App\Models\JasaMedis::with(['pegawai' => function ($q) {
            $q->select('nik', 'nama');
        }])->where('kd_dokter', $nik)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return new \App\Http\Resources\RealDataCollection($jasaMedis);
    }

    public function jaspel($nik, Request $request)
    {
        $jasaPelayanan = \App\Models\JasaPelayanan::with(['pegawai' => function ($q) {
            $q->select('nik', 'nama');
        }, 'jasa_pelayanan_akun' => function ($q) {
            $q->where('id_akun', 12);
        }])->where('nik', $nik)
            ->where('status_payroll', '1')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return new \App\Http\Resources\RealDataCollection($jasaPelayanan);
    }
}
