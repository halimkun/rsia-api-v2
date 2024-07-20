<?php

namespace App\Http\Controllers\v2;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\RateLimiter;
use App\Traits\ThrottlesAttempts;

class UserAuthController extends Controller
{
    use ThrottlesAttempts;

    public function login(Request $request)
    {
        // Memeriksa apakah terlalu banyak percobaan login
        if ($this->hasTooManyAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::select(
            DB::raw('AES_DECRYPT(id_user, "' . env('MYSQL_AES_KEY_IDUSER') . '") as id_user'),
            DB::raw('AES_DECRYPT(id_user, "' . env('MYSQL_AES_KEY_IDUSER') . '") as username'),
            DB::raw('AES_DECRYPT(password, "' . env('MYSQL_AES_KEY_PASSWORD') . '") as password')
        )
            ->where('id_user', DB::raw('AES_ENCRYPT("' . $credentials['username'] . '", "' . env('MYSQL_AES_KEY_IDUSER') . '")'))
            ->where('password', DB::raw('AES_ENCRYPT("' . $credentials['password'] . '", "' . env('MYSQL_AES_KEY_PASSWORD') . '")'))
            ->first();

        if (!$user) {
            $this->incrementAttempts($request);
            return \App\Helpers\ApiResponse::error('User not found', 'unauthorized', null, 401);
        }

        // Auth berhasil, bersihkan percobaan login
        $this->clearAttempts($request);

        // // user found in database loggin in the user
        \Illuminate\Support\Facades\Auth::guard('user-aes')->setUser($user);

        $token = $user->createToken($credentials['username'])->accessToken;
        $token_type = 'Bearer';
        // $token_expires_at = $user->tokens->first()->expires_at;
        // $token_expores_in = $user->tokens->first()->expires_at->diffForHumans(); get seconds
        $token_expores_in = $user->tokens->first()->expires_at->diffInSeconds();

        return \App\Helpers\ApiResponse::withToken(true, $token, [
            'token_type'    => $token_type,
            // 'expires_at'    => $token_expires_at,
            'expires_in'    => $token_expores_in,
        ]);
    }

    public function logout()
    {
        // laravel passport revokes the token
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();

        if (!$user) {
            return \App\Helpers\ApiResponse::error('User not found', 'unauthorized', null, 401);
        }

        $user->token()->revoke();

        return \App\Helpers\ApiResponse::success('User logged out successfully');
    }

    public function detail()
    {
        $user = \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
        return new \App\Http\Resources\User\Auth\DetailResource($user);
    }
}
