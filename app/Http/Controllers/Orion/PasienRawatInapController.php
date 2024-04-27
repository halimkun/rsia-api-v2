<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;

class PasienRawatInapController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\KamarInap::class;

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
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['no_rawat', 'kd_kamar', 'tgl_masuk', 'tgl_keluar', 'lama', 'stts_pulang'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_rawat', 'kd_kamar', 'tgl_masuk', 'tgl_keluar', 'lama', 'stts_pulang'];
    }

    /**
    * The relations and fields that are allowed to be aggregated on a resource.
    *
    * @return array
    */
    public function aggregates() : array
    {
        return ['pasien.nm_pasien', 'pasien.no_rkm_medis', 'pasien.jk', 'pasien.tmp_lahir', 'pasien.tgl_lahir'];
    }

    /**
     * The relations that are always included together with a resource.
     *
     * @return array
     */
    public function alwaysIncludes(): array
    {
        return ['pasien']; // TODO : fix only select fields that are needed
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return ['regPeriksa', 'pasien'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_rawat', 'pasien.nm_pasien', 'pasien.no_rkm_medis'];
    }
}
