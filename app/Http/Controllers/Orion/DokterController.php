<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;

class DokterController extends \Orion\Http\Controllers\Controller
{
    /**
     * Disable authorization for all actions
     * 
     * @var bool
     * */ 
    use \Orion\Concerns\DisableAuthorization;

    /**
     * Model class for Dokter
     * 
     * @var string
     * */ 
    protected $model = \App\Models\Dokter::class;

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
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['kd_dokter', 'nm_dokter', 'tgl_lahir', 'no_ijn_praktek'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['kd_doktter', 'jk', 'tmp_lahir'. 'gol_darah', 'agama', 'stts_nikah', 'kd_sps', 'status', 'jadwal.hari_kerja', 'jadwal.jam_mulai', 'jadwal.jam_selesai', 'jadwal.kd_poli', 'jadwal.kuota'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['kd_dokter', 'almt_tgl', 'nm_dokter', 'tgl_lahir', 'no_ijn_praktek', 'alumni'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return ['spesialis', 'jadwal'];
    }
}
