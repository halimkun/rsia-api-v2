<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;

class BridgingSepController extends \Orion\Http\Controllers\Controller
{
    use \Orion\Concerns\DisableAuthorization;

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

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_rawat', 'tglsep', 'tglrujukan', 'nama_pasien'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['no_sep', 'no_rawat', 'klsrawat', 'nama_pasien', 'no_kartu', 'nomr', 'jnspelayanan'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_sep', 'no_rawat', 'klsrawat', 'nama_pasien', 'no_kartu', 'nomr'];
    }

    /**
     * The relations that are used for including.
     * 
     * @return array
     * */ 
    public function includes(): array
    {
        return ['reg_periksa', 'kamar_inap', 'chunk'];
    }
}
