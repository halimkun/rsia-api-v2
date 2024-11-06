<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusKlaimSepController extends Controller
{
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
        $request->validate([
            'month' => 'required|date_format:Y-m'
        ]);

        [$year, $month] = explode('-', $request->month);

        $data = \App\Models\RsiaStatusKlaim::withCount(['sep'])
            ->whereHas('sep', function ($query) use ($year, $month) {
                $query->whereYear('tglsep', $year)->whereMonth('tglsep', $month);
            })->select('status', \DB::raw('count(*) as total'))->groupBy('status')->get();

        // map statuses to status if statuses not found in data then set total to 0
        $data = collect($this->statuses)->map(function ($status) use ($data) {
            $status = strtolower($status);
            $total = $data->firstWhere('status', $status);
            return [
                'status' => $status,
                'total' => $total ? $total->total : 0
            ];
        });

        sleep(1.3);

        return ApiResponse::successWithData($data, "Data status klaim bulan $year-$month");
    }
}
