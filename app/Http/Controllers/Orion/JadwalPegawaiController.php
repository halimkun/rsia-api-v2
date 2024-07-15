<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;

// TODO : make this works
class JadwalPegawaiController extends \Orion\Http\Controllers\Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\JadwalPegawai::class;

    /**
     * Retrieves currently authenticated user based on the guard.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function resolveUser()
    {
        return \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
    }
}
