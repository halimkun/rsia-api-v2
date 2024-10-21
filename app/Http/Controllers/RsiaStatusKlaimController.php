<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreRsiaStatusKlaimRequest;
use App\Http\Requests\UpdateRsiaStatusKlaimRequest;
use App\Models\RsiaStatusKlaim;
use DB;

class RsiaStatusKlaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

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
     * @param  \App\Http\Requests\StoreRsiaStatusKlaimRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRsiaStatusKlaimRequest $request, $no_sep)
    {
        if ($request->no_sep != $no_sep) {
            return ApiResponse::error("resource not found", "No sep not found");
        }
     
        $regPeriksa = \App\Models\RegPeriksa::where('no_rawat', $request->no_rawat)->first();
        if (!$regPeriksa) {
            return ApiResponse::error("resource not found", "No rawat not found");
        }

        $statusKlaimData = [
            'no_sep'         => $request->no_sep,
            'no_rawat'       => $request->no_rawat,
            'status'         => $request->status,
        ];
        $mLiteVedikaData = [
            'tanggal'        => \Carbon\Carbon::now()->format('Y-m-d'),
            'no_rkm_medis'   => $regPeriksa->no_rkm_medis,
            'no_rawat'       => $request->no_rawat,
            'tgl_registrasi' => $regPeriksa->tgl_registrasi,
            'nosep'          => $request->no_sep,
            'jenis'          => $regPeriksa->status_lanjut == "Ranap" ? 1 : 2,
            'status'         => $request->status,
            'username'       => $request->user()->id_user,
        ];
        $mLiteVedikaFeedbackData = [
            'nosep'          => $request->no_sep,
            'tanggal'        => \Carbon\Carbon::now()->format('Y-m-d'),
            'catatan'        => $request->feedback,
            'username'       => $request->user()->id_user,
        ];

        // Insert to mLiteVedika
        try {
            DB::transaction(function () use ($statusKlaimData, $mLiteVedikaData, $mLiteVedikaFeedbackData, $request) {
                // Vedika
                $vedika = \App\Models\MLiteVedika::create($mLiteVedikaData);

                // Vedika feedback
                if ($request->has('feedback') && (!empty($request->feedback) || $request->feedback != null)) {
                    // add created_at data from mLiteVedika to mLiteVedikaFeedback
                    $mLiteVedikaFeedbackData['created_at'] = $vedika->created_at;
                    $mLiteVedikaFeedbackData['updated_at'] = $vedika->updated_at;


                    \App\Models\MLiteVedikaFeeedback::create($mLiteVedikaFeedbackData);
                }

                // klaim status
                RsiaStatusKlaim::updateOrCreate(
                    ['no_sep' => $request->no_sep],
                    $statusKlaimData
                );
            }, 5);
        } catch (\Throwable $th) {
            return ApiResponse::error("Failed to update status klaim", $th->getMessage());
        }

        return ApiResponse::success("Status klaim updated successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RsiaStatusKlaim  $rsiaStatusKlaim
     * @return \Illuminate\Http\Response
     */
    public function show(RsiaStatusKlaim $rsiaStatusKlaim)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RsiaStatusKlaim  $rsiaStatusKlaim
     * @return \Illuminate\Http\Response
     */
    public function edit(RsiaStatusKlaim $rsiaStatusKlaim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRsiaStatusKlaimRequest  $request
     * @param  \App\Models\RsiaStatusKlaim  $rsiaStatusKlaim
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRsiaStatusKlaimRequest $request, RsiaStatusKlaim $rsiaStatusKlaim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RsiaStatusKlaim  $rsiaStatusKlaim
     * @return \Illuminate\Http\Response
     */
    public function destroy(RsiaStatusKlaim $rsiaStatusKlaim)
    {
        //
    }
}
