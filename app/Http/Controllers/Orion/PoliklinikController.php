<?php

namespace App\Http\Controllers\Orion;

use Orion\Http\Requests\Request;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Database\Eloquent\Builder;

class PoliklinikController extends \Orion\Http\Controllers\Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\Poliklinik::class;

    /**
     * Builds Eloquent query for fetching entities in index method.
     *
     * @param Request $request
     * @param array $requestedRelations
     * @return Builder
     */
    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);

        $query->where('status', '1')->where('kd_poli', '!=', '-')->orderBy('nm_poli');

        return $query;
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

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['kd_poli', 'status', 'jadwal_dokter.kuota', 'jadwal_dokter.kd_dokter', 'jadwal_dokter.hari_kerja', 'jadwal_dokter.jam_mulai', 'jadwal_dokter.jam_selesai'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['kd_poli', 'status', 'nm_poli'];
    }

    /**
     * The relations and fields that are allowed to be aggregated on a resource.
     *
     * @return array
     */
    public function aggregates(): array
    {
        return ['jadwal_dokter', 'jadwal_dokter.poliklinik', 'jadwal_dokter.dokter'];
    }

    /**
     * The relations that are always included together with a resource.
     *
     * @return array
     */
    public function alwaysIncludes(): array
    {
        return [];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return ['jadwal_dokter', 'jadwal_dokter.poliklinik', 'jadwal_dokter.dokter'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['jd_poli', 'nm_poli'];
    }
}
