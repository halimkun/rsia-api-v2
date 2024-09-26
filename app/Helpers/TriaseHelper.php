<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class TriaseHelper
{
    public static function cekPemeriksaan(Collection $skala, string $namaPemeriksaan, bool $returnBoolean = false)
    {
        return $skala->contains(function ($item) use ($namaPemeriksaan) {
            return Str::lower($item->master->pemeriksaan->nama_pemeriksaan) == Str::lower($namaPemeriksaan);
        }) ? ($returnBoolean ? true : '&#9745;') : ($returnBoolean ? false : '&#9744;');
    }

    public static function multiCheckPemeriksaaan(Collection $skala, string $namaPemeriksaan, string $pengkajian, bool $returnBoolean = false)
    {
        if ($skala->contains(function ($item) use ($namaPemeriksaan) {
            return Str::lower($item->master->pemeriksaan->nama_pemeriksaan) == Str::lower($namaPemeriksaan);
        })) {
            if ($skala->contains(function ($item) use ($pengkajian) {
                return Str::lower($item->master->pengkajian_skala1) == Str::lower($pengkajian);
            })) {
                return $returnBoolean ? true : '&#9745;';
            } else {
                return $returnBoolean ? false : '&#9744;';
            }
        } else {
            return $returnBoolean ? false : '&#9744;';
        }
    }
}
