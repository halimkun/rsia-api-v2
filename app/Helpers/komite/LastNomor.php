<?php

namespace App\Helpers\komite;

class LastNomor
{
    public static function get($model, $tgl_terbit)
    {
        $year = date('Y', strtotime($tgl_terbit));
        $nomor = $model::whereYear('tgl_terbit', $year)->max('nomor');

        if (!$nomor) {
            return 1;
        }

        return $nomor + 1;
    }
}
