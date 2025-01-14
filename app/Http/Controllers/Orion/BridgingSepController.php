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
        return [
            'hasBerkasPerawatan',
            'notHasBerkasPerawatan',
            'notHasStatusKlaim',
            'selectColumns',
        ];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return [
            'tglsep',
            'no_rawat',
            'tglrujukan',
            'nama_pasien',
            'status_klaim.status',
            'reg_periksa.tgl_registrasi',
            'reg_periksa.jam_reg',
            'kamar_inap.tgl_keluar',
            'kamar_inap.jam_keluar',
        ];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return [
            'nomr',
            'tglsep',
            'no_sep',
            'no_rawat',
            'no_kartu',
            'klsrawat',
            'nama_pasien',
            'jnspelayanan',
            'status_klaim.status',
            'groupStage.code_cbg',
            'reg_periksa.kd_poli',
            'reg_periksa.tgl_registrasi',
            'kamar_inap.tgl_keluar',
            'kamar_inap.jam_keluar',
        ];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return [
            'nomr',
            'no_sep',
            'no_rawat',
            'no_kartu',
            'klsrawat',
            'nama_pasien',
            'dokter.nm_dokter',
            'poliklinik.nm_poli'
        ];
    }

    /**
     * The relations that are used for including.
     * 
     * @return array
     * */
    public function includes(): array
    {
        return [
            'chunk',
            'pasien',
            'kamar_inap',
            'groupStage',
            'status_klaim',
            'tanggal_pulang',
            'berkasPerawatan',
            'terkirim_online',
            'status_klaim.log',

            'reg_periksa',
            'reg_periksa.dokter',
            'reg_periksa.poliklinik',
            'reg_periksa.dokter.spesialis',
        ];
    }
}
