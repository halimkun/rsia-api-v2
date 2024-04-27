<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class PasienController extends Controller
{
    use DisableAuthorization;

    protected $model = \App\Models\Pasien::class;

    public function resolveUser()
    {
        return \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
    }

    public function filterableBy(): array
    {
        return ['jk', 'tmp_lahir', 'tgl_lahir', 'gol_darah', 'stts_nikah', 'agama', 'pnd', 'kd_pj'];
    }

    public function sortableBy(): array
    {
        return ['no_rkm_medis', 'nm_pasien', 'tgl_lahir', 'jk', 'tmp_lahir'];
    }

    public function aggregates(): array
    {
        return [];
    }

    public function alwaysIncludes(): array
    {
        return [];
    }

    public function includes(): array
    {
        return [];
    }

    public function searchableBy(): array
    {
        return ['no_rkm_medis', 'nm_pasien'];
    }
}
