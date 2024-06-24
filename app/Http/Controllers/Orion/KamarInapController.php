<?php

namespace App\Http\Controllers\Orion;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class KamarInapController extends \Orion\Http\Controllers\Controller
{
    use \Orion\Concerns\DisableAuthorization;

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
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_rawat', 'tgl_masuk', 'tgl_keluar' ,'jam_masuk', 'jam_keluar', 'lama', 'ttl_biaya' ,'trf_kamar', 'stts_pulang'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['no_rawat', 'kd_kamar', 'diagnosa_awal', 'tgl_masuk', 'tgl_keluar' ,'jam_masuk', 'jam_keluar', 'lama', 'stts_pulang', 'pasien.jk'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_rawat', 'kd_kamar', 'diagnosa_awal', 'tgl_masuk', 'tgl_keluar' ,'jam_masuk', 'jam_keluar', 'lama', 'stts_pulang', 'pasien.no_rkm_medis', 'pasien.nm_pasien'];
    }

    /**
     * The relations that are used for including.
     * 
     * @return array
     * */
    public function includes(): array
    {
        return ['pasien', 'regPeriksaSimple', 'sep'];
    }
}
