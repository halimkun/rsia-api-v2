<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Concerns\DisableAuthorization;

class PresensiKaryawanController extends \Orion\Http\Controllers\RelationController
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\Pegawai::class;

    /**
     * Name of the relationship as it is defined on the Post model
     */
    protected $relation = 'presensi';

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
     * The list of available query scopes.
     *
     * @return array
     */
    public function exposedScopes(): array
    {
        return ['withId', 'withDatang'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['id', 'shift', 'jam_datang', 'jam_pulang', 'status', 'keterlambatan', 'durasi', 'keterangan', 'photo'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['shift', 'jam_datang', 'jam_pulang', 'status'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return [];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['id', 'shift', 'jam_datang', 'jam_pulang', 'status', 'keterlambatan', 'durasi', 'keterangan', 'photo'];
    }
}
