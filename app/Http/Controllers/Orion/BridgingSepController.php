<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;

class BridgingSepController extends \Orion\Http\Controllers\Controller
{
    /**
     * The model class name used in the controller.
     *
     * @var string
     */
    use \Orion\Concerns\DisableAuthorization;

    /**
     * The model class name used in the controller.
     *
     * @var string
     */
    protected $model = \App\Models\BridgingSep::class;

    /**
     * Retrieves currently authenticated user based on the guard.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function resolveUser()
    {
        return \Illuminate\Support\Facades\Auth::guard('user-aes')->user();
    }

    public function exposedScopes(): array
    {
        return ['hasBerkasPerawatan'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_rawat', 'tglsep', 'tglrujukan', 'nama_pasien', 'reg_periksa.tgl_registrasi', 'reg_periksa.jam_reg', 'status_klaim.status'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['no_sep', 'no_rawat', 'klsrawat', 'nama_pasien', 'no_kartu', 'nomr', 'jnspelayanan', 'reg_periksa.tgl_registrasi', 'status_klaim.status'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_sep', 'no_rawat', 'klsrawat', 'nama_pasien', 'no_kartu', 'nomr', 'dokter.nm_dokter'];
    }

    /**
     * The relations that are used for including.
     * 
     * @return array
     * */
    public function includes(): array
    {
        return ['reg_periksa', 'reg_periksa.poliklinik', 'reg_periksa.dokter', 'reg_periksa.dokter.spesialis', 'kamar_inap', 'chunk', 'tanggal_pulang', 'status_klaim', 'status_klaim.log', 'groupStage', 'pasien', 'berkasPerawatan'];
    }
}
