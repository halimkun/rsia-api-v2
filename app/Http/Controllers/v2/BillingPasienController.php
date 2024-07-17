<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($base64_no_rawat)
    {
        // check if base64_no_rawat is valid base64
        if (!base64_decode($base64_no_rawat, true)) {
            return \App\Helpers\ApiResponse::error('Invalid parameter : base64_no_rawat is not a valid base64 string', 'invalid_params', 400);
        }

        $no_rawat = base64_decode($base64_no_rawat);

        $data = \App\Models\RegPeriksa::where('no_rawat', $no_rawat)->first();
        
        if (!$data) {
            return \App\Helpers\ApiResponse::error('Billing Data not found', 'resource_not_found', null, 404);
        }

        return new \App\Http\Resources\Pasien\Ranap\Billing\BillingPasienRanap($no_rawat);
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
