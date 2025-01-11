<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class JurnalHelper
{
    public static function determinePetugas($no_rawat)
    {
        $jurnal = \App\Models\Jurnal::where('no_bukti', $no_rawat)
            ->where('keterangan', 'like', 'TINDAKAN%')
            ->orderBy('tgl_jurnal', 'desc')->orderBy('jam_jurnal', 'desc')->first();

        if (!$jurnal) {
            return null;
        }

        $keterangan = Str::lower($jurnal->keterangan);
        $explodedKeterangan = explode(' ', $keterangan);

        $nikPetugas = end($explodedKeterangan);

        $petugas = \App\Models\Petugas::with('sidikjari')->where('nip', $nikPetugas)->first();

        if (!$petugas) {
            return null;
        }

        return $petugas;
    }
}
