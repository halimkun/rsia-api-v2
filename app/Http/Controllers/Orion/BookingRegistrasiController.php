<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class BookingRegistrasiController extends Controller
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
    protected $model = \App\Models\BookingRegistrasi::class;

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
        return ['tanggal_booking', 'jam_booking', 'no_rkm_medis', 'tanggal_periksa', 'kd_dokter', 'kd_poli', 'no_reg', 'kd_pj', 'limit_reg', 'waktu_kunjungan', 'status'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['tanggal_booking', 'no_rkm_medis', 'tanggal_periksa', 'kd_dokter', 'kd_poli', 'no_reg', 'kd_pj', 'limit_reg', 'waktu_kunjungan', 'status'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['tanggal_booking', 'no_rkm_medis', 'tanggal_periksa', 'kd_dokter', 'kd_poli', 'no_reg', 'kd_pj', 'limit_reg', 'waktu_kunjungan', 'status', 'dokter.nm_dokter', 'penjab.png_jawab', 'pasien.nm_pasien'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return ['dokter', 'dokter.spesialis', 'penjab', 'pasien', 'poli'];
    }
}
