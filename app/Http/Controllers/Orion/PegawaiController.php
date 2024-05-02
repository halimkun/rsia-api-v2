<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;

class PegawaiController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\Pegawai::class;

    /**
     * Retrieves currently authenticated user based on the guard.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function resolveUser()
    {
        return \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['nik', 'nama'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['stts_aktif'];
    }
}
