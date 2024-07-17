<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BridgingEKlaim extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // check apakah didalam request terdapat metadata dan apakah didalam metadata terdapat method
        if (!$request->has('metadata') && !$request->metadata->has('method')) {
            return ApiResponse::error('Request must have metadata and method', 'invalid_request', null, 400);
        }

        $stringified = json_encode($request->all());
        $request_data = \App\Helpers\EKlaimCrypt::encrypt($stringified);
        
        // Ensure request_data is correctly formatted as a single-line string
        $request_data = trim(preg_replace('/\s+/', ' ', $request_data)); // <--- This line is the key to the solution

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post('http://192.168.100.45/E-Klaim/ws.php', [
                $request_data
            ]);
        } catch (\Throwable $th) {
            return ApiResponse::error("Error : INACBG's invalid request", "invalid_inacbg_request", $th->getMessage(), 500);
        }

        $first = strpos($response, "\n")+1;
        $last  = strrpos($response, "\n")-1;
        $data  = substr($response, $first, strlen($response) - $first - $last);

        try {
            $resp = json_decode(\App\Helpers\EKlaimCrypt::decrypt($data));
        } catch (\Throwable $th) {
            return ApiResponse::error('Error : '.$th->getMessage(), "invalid_inacbg_decrypt", $th->getCode(), 500);
        }

        if ($resp->metadata->code != 200) {
            return ApiResponse::error($resp->metadata->message, "invalid_inacbg_request", $resp, $resp->metadata->code);
        }

        return $resp;
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
