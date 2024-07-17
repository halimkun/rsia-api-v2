<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PasienAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'no_rkm_medis' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['no_rkm_medis', 'password']);
        $credentials['password'] = substr($credentials['password'], 0, 2) . '-' . substr($credentials['password'], 2, 2) . '-' . substr($credentials['password'], 4, 4);
        $credentials['password'] = date('Y-m-d', strtotime($credentials['password']));

        $pasien = \App\Models\Pasien::select('no_rkm_medis', 'nm_pasien', 'tgl_lahir', 'alamat', 'no_tlp', 'email')
            ->where('no_rkm_medis', $credentials['no_rkm_medis'])->where('tgl_lahir', $credentials['password'])->first();

        if (!$pasien) {
            return \App\Helpers\ApiResponse::error('Gagal login, periksa kembali nomor rekam medis dan tanggal lahir anda', 'unauthorized', null, 401);
        }

        // login or set user to auth guard pasien
        \Illuminate\Support\Facades\Auth::guard('pasien')->setUser($pasien);

        $token = $pasien->createToken($credentials['no_rkm_medis'])->accessToken;
        $token_type = 'Bearer';
        $token_expires_at = $pasien->tokens->first()->expires_at;
        $token_expores_in = $pasien->tokens->first()->expires_at->diffForHumans();

        return \App\Helpers\ApiResponse::withToken(true, $token, [
            'token_type'    => $token_type,
            'expires_at'    => $token_expires_at,
            'expires_in'    => $token_expores_in,
        ]);
    }

    public function detail()
    {
        $pasien = \Illuminate\Support\Facades\Auth::guard('pasien')->user();
        return new \App\Http\Resources\Pasien\Auth\DetailResource($pasien);
    }
}
