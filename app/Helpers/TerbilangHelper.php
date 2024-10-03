<?php

namespace App\Helpers;

class TerbilangHelper
{
    private static $words = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'
    ];

    public static function terbilang($number)
    {
        if ($number < 12) {
            return self::$words[$number];
        } elseif ($number < 20) {
            return self::terbilang($number - 10) . ' belas';
        } elseif ($number < 100) {
            return self::terbilang($number / 10) . ' puluh ' . self::terbilang($number % 10);
        } elseif ($number < 200) {
            return 'seratus ' . self::terbilang($number - 100);
        } elseif ($number < 1000) {
            return self::terbilang($number / 100) . ' ratus ' . self::terbilang($number % 100);
        } elseif ($number < 2000) {
            return 'seribu ' . self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::terbilang($number / 1000) . ' ribu ' . self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::terbilang($number / 1000000) . ' juta ' . self::terbilang($number % 1000000);
        } else {
            return 'Angka terlalu besar';
        }
    }
}