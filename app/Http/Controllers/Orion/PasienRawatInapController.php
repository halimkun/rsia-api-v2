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
     * @var string $resource
     */
    protected $resource = \App\Http\Resources\Pasien\Ranap\PasienRanapResource::class;

    /**
     * @var string $collectionResource
     */
    protected $collectionResource = \App\Http\Resources\Pasien\Ranap\PasienRanapCollection::class;

    /**
     * Default pagination limit.
     *
     * @return int
     */
    public function limit(): int
    {
        return 10;
    }

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
        return ['hasBerkasPerawatan', 'notHasBerkasPerawatan', 'notHasStatusKlaim'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return [
            'no_rawat', 
            'kd_kamar', 
            'tgl_masuk', 
            'tgl_keluar', 
            'jam_masuk', 
            'jam_keluar', 
            'stts_pulang', 

            'regPeriksa.tgl_registrasi', 
            'regPeriksa.kd_pj', 
            
            'sep.status_klaim.status'
        ];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_rawat', 'kd_kamar', 'tgl_masuk', 'tgl_keluar', 'stts_pulang', 'regPeriksa.tgl_registrasi'];
    }

    /**
     * The relations and fields that are allowed to be aggregated on a resource.
     *
     * @return array
     */
    public function aggregates(): array
    {
        return [];
        // return ['pasien.nm_pasien', 'lama', 'lamaInap', 'lamaInap.lama', 'pasien.no_rkm_medis', 'pasien.jk', 'pasien.tmp_lahir', 'pasien.tgl_lahir'];
    }

    /**
     * The relations that are always included together with a resource.
     *
     * @return array
     */
    public function alwaysIncludes(): array
    {
        return ['pasien'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return [
            'regPeriksa', 
            
            'pasien', 
            
            'lamaInap', 
            
            'sep',
            
            'sepSimple', 
            'sepSimple.status_klaim', 
            'sepSimple.terkirim_online', 
            'sepSimple.berkasPerawatan', 
            
            'regPeriksaSimple',
            'regPeriksaSimple.dokter', 
            'regPeriksaSimple.poliklinik', 
        ];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_rawat', 'pasien.nm_pasien', 'pasien.no_rkm_medis', 'sep.no_sep', 'regPeriksaSimple.kd_pj', 'regPeriksaSimple.no_rkm_medis', 'regPeriksaSimple.no_rawat', 'regPeriksaSimple.kd_poli', 'regPeriksaSimple.dokter.nm_dokter', 'regPeriksaSimple.poliklinik.nm_poli'];
    }
}
