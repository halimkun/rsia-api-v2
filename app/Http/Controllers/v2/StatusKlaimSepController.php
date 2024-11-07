<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusKlaimSepController extends Controller
{
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

        // Retrieve data grouped by jnspelayanan and status_klaim status
        $data = \App\Models\BridgingSep::with('status_klaim')
            ->whereHas('status_klaim') // Ensure status_klaim exists
            ->whereYear('tglsep', $year)
            ->whereMonth('tglsep', $month)
            ->join('rsia_status_klaim', 'bridging_sep.no_sep', '=', 'rsia_status_klaim.no_sep') // Join with the status_klaim table
            ->select('bridging_sep.no_sep', 'bridging_sep.jnspelayanan', 'rsia_status_klaim.status') // Select from both tables
            ->get()
            ->groupBy(['jnspelayanan', 'status_klaim.status']); // Group by jnspelayanan and status

        // Format the result as needed
        $formattedData = collect($this->jnsPelayanan)->mapWithKeys(function ($jnsPelayanan) use ($data) {
            $jnsPelayanan = (int) $jnsPelayanan;

            // Prepare status group translation
            $statusDetails = collect($this->statuses)->mapWithKeys(function ($status) use ($data, $jnsPelayanan) {
                $status = strtolower($status);

                // Get the claims for this status and jnspelayanan
                $claims = $data->get($jnsPelayanan, collect())->get($status, collect());

                // Return the count of claims for this status
                return [
                    $status => $claims->count()
                ];
            });

            // Translate jnsPelayanan to readable form
            $translatedJnsPelayanan = [
                1 => 'Rawat Inap',
                2 => 'Rawat Jalan'
            ];

            return [
                $translatedJnsPelayanan[$jnsPelayanan] => $statusDetails
            ];
        });

        // Return the data in the API response
        return ApiResponse::successWithData($formattedData, "Data status klaim bulan $year-$month");
    }
}
