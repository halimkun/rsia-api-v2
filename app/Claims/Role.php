<?php

namespace App\Claims;

use CorBosman\Passport\AccessToken;

class Role
{
    public function handle(AccessToken $token, $next)
    {
        $user_identifier = $token->getUserIdentifier();

        // example user identifier is 3.928.0163, 2.928.0163, 1.928.0163
        if (!preg_match("/^\d{1}\.\d{3}\.\d{4}$/", $user_identifier)) {
            $token->addClaim('role', 'pasien');
        } else {
            $role = self::checkNik($user_identifier);
            $token->addClaim('role', $role);
        }

        return $next($token);
    }

    private static function checkNik($nik)
    {
        if (\App\Models\Dokter::where('kd_dokter', $nik)->where('status', '1')->exists()) {
            return 'dokter';
        } else {
            return 'pegawai';
        }
    }
}
