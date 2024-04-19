<?php

namespace App\Claims;

use CorBosman\Passport\AccessToken;

class Departemen
{
    public function handle(AccessToken $token, $next)
    {
        $user_identifier = $token->getUserIdentifier();

        // example user identifier is 3.928.0163, 2.928.0163, 1.928.0163
        if (preg_match("/^\d{1}\.\d{3}\.\d{4}$/", $user_identifier)) {
            $pegawai = self::getPegawaiDetail($user_identifier);

            $token->addClaim('jbtn', $pegawai->jbtn);
            $token->addClaim('dep', $pegawai->departemen);

            // if ($pegawai->dep) {
            //     $token->addClaim('dep_name', $pegawai->dep->nama);
            // }
        }

        return $next($token);
    }

    private static function getPegawaiDetail($nik)
    {
        return \App\Models\Pegawai::select('nik', 'nama', 'alamat', 'jk', 'jbtn', 'departemen')
            // ->with('dep')
            ->where('nik', $nik)
            ->first();
    }
}
