<?php

namespace App\Helpers;

class NaikKelasHelper
{
    public static function translate($kelasNaik) 
    {

        if (!is_numeric($kelasNaik) || $kelasNaik < 1 || $kelasNaik > 8) {
            return "Kelas naik harus berupa angka antara 1-8";
        }

        $data = [
            '1' => 'VVIP',
            '2' => 'VIP',
            '3' => 'Kelas I',
            '4' => 'Kelas II',
            '5' => 'Kelas III',
            '6' => 'ICCU',
            '7' => 'ICU',
            '8' => 'Diatas Kelas 1',
        ];

        return $data[$kelasNaik];
    }
}
