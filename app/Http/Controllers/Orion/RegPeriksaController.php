<?php

namespace App\Http\Controllers\Orion;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class RegPeriksaController extends \Orion\Http\Controllers\Controller
{
    use \Orion\Concerns\DisableAuthorization;

    protected $model = \App\Models\RegPeriksa::class;

    protected $collectionResource = \App\Http\Resources\RegistrasiPeriksaCollection::class;

    /**
     * Runs the given query for fetching entity in show method.
     *
     * @param Request $request
     * @param Builder $query
     * @param int|string $key
     * @return Model
     */
    protected function runShowFetchQuery(Request $request, \Illuminate\Database\Eloquent\Builder $q, $key): \Illuminate\Database\Eloquent\Model
    {
        try {
            $key = base64_encode(base64_decode($key)) === $key ? base64_decode($key) : $key;
        } catch (\Exception $e) {
            return ApiResponse::error('Invalid key', 'Key must be a valid base64 string');
        }

        return $this->runFetchQuery($request, $q, $key);
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
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_reg', 'no_rawat', 'tgl_registrasi', 'jam_reg', 'kd_poli'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['no_rawat', 'no_rkm_medis', 'tgl_registrasi', 'kd_poli', 'kd_dokter', 'stts', 'stts_daftar', 'status_lanjut', 'kd_pj', 'status_bayar', 'status_poli', 'pasien.jk'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_rawat', 'no_rkm_medis', 'p_jawab', 'almt_pj', 'pasien.nm_pasien', 'pasien.no_ktp'];
    }

    /**
     * The relations that are used for including.
     * 
     * @return array
     * */
    public function includes(): array
    {
        return ['pasien', 'dokter', 'poliklinik', 'dokter.spesialis', 'pasienBayi', 'caraBayar', 'pemeriksaanRalan', 'pemeriksaanRanap'];
    }
}
