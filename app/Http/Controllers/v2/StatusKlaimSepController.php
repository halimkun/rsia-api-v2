<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusKlaimSepController extends Controller
{
    // 1 = Rawat Inap, 2 = Rawat Jalan
    protected $jnsPelayanan = [1, 2];

    protected $statuses = [
        'verifikasi resume',
        'lengkap',
        'pengajuan',
        'perbaiki',
        'disetujui',
        'klaim ambulans',
        'batal',
        'pending'
    ];

    public function search(Request $request)
    {
        // Validate the month input
        $request->validate([
            'month' => 'required|date_format:Y-m'
        ]);

        // Extract year and month from the input
        [$year, $month] = explode('-', $request->month);

        $prevMonth = ($month === 1) ? 12 : $month - 1;
        $prevYear = ($month === 1) ? $year - 1 : $year;

        // Rawat Inap
        $ri = \App\Models\KamarInap::select('no_rawat', 'tgl_masuk', 'jam_masuk', 'tgl_keluar', 'jam_keluar')
            ->with(['sep.status_klaim', 'sep.berkasPerawatan' => function ($query) {
                $query->where('kode', '009');
            }])
            ->whereHas('sep')
            ->whereYear('tgl_keluar', $year)
            ->whereMonth('tgl_keluar', $month)
            ->where('stts_pulang', '!=', 'Pindah Kamar')
            ->get();

        $d['Rawat Inap'] = [
            "total_sep" => $ri->count(),
            "total_berkas_terkirim" => $ri->filter(function ($item) {
                return $item->sep->berkasPerawatan != null;
            })->count(),
            "total_sep_last_month" => $this->getJumlahSep(1, $prevYear, $prevMonth),
        ];

        $rj_status = $ri->groupBy('sep.status_klaim.status');
        foreach ($this->statuses as $status) {
            $d['Rawat Inap']['status'][$status] = $rj_status->get($status, collect())->count();
        }

        // Rawat Jalan
        $rj = \App\Models\BridgingSep::select('no_sep', 'jnspelayanan', 'tglsep')
            ->where('jnspelayanan', 2)
            ->with('status_klaim')
            ->whereYear('tglsep', $year)
            ->whereMonth('tglsep', $month)
            ->get();

        $d['Rawat Jalan'] = [
            "total_sep" => $rj->count(),
            "total_berkas_terkirim" => $this->getJumlahBerkasTerkirim(2, $year, $month),
            "total_sep_last_month" => $this->getJumlahSep(2, $prevYear, $prevMonth),
        ];

        $ri_status = $rj->groupBy('status_klaim.status');
        foreach ($this->statuses as $status) {
            $d['Rawat Jalan']['status'][$status] = $ri_status->get($status, collect())->count();
        }

        return ApiResponse::successWithData($d, "Data status klaim bulan $year-$month");
    }

    protected function getJumlahSep($jnsPelayanan, $year, $month)
    {
        return \App\Models\BridgingSep::where('jnspelayanan', $jnsPelayanan)
            ->whereYear('tglsep', $year)
            ->whereMonth('tglsep', $month)
            ->count();
    }

    protected function getJumlahBerkasTerkirim($jnsPelayanan, $year, $month)
    {
        return \App\Models\BridgingSep::where('jnspelayanan', $jnsPelayanan)
            ->whereYear('tglsep', $year)
            ->whereMonth('tglsep', $month)
            ->whereHas('berkasPerawatan', function ($query) {
                $query->where('kode', '009');
            })
            ->count();
    }
}
