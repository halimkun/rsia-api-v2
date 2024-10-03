<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\TokenRepository;

class HandleTokensController extends Controller
{
    public function index()
    {
        // get tokens from database
        $tokens = Passport::token()->get();
        
        $datas = $tokens->makeVisible('id');

        return view('app.token.index', [
            'tokens' => $datas,
        ]);
    }

    public function revoke($id)
    {
        // revoke token
        $token = Passport::token()->where('id', $id)->first();
        $token->revoke();

        return redirect()->route('oauth.token.index')->with('success', 'Token berhasil dicabut');
    }

    public function destroy($id)
    {
        // delete token
        $token = Passport::token()->where('id', $id)->first();
        $token->delete();

        return redirect()->route('oauth.token.index')->with('success', 'Token berhasil dihapus');
    }

    public function deleteExpired()
    {
        // delete expired token
        $tokens = Passport::token()->where('expires_at', '<', now())->get();
        $tokens->each(function ($token) {
            $token->delete();
        });

        // delete expired refresh token
        $refreshTokens = Passport::refreshToken()->where('expires_at', '<', now())->get();
        $refreshTokens->each(function ($token) {
            $token->delete();
        });

        return redirect()->route('oauth.token.index')->with('success', 'Token expired berhasil dihapus');
    }

    public function deleteRevoked()
    {
        // delete revoked token
        $tokens = Passport::token()->where('revoked', 1)->get();
        $tokens->each(function ($token) {
            $token->delete();
        });

        // delete revoked refresh token
        $refreshTokens = Passport::refreshToken()->where('revoked', 1)->get();
        $refreshTokens->each(function ($token) {
            $token->delete();
        });

        return redirect()->route('oauth.token.index')->with('success', 'Token revoked berhasil dihapus');
    }
}
