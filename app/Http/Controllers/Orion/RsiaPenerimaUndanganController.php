<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;

class RsiaPenerimaUndanganController extends \Orion\Http\Controllers\Controller
{
    use \Orion\Concerns\DisableAuthorization;

    protected $model = \App\Models\RsiaPenerimaUndangan::class;

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
        return ['no_surat', 'penerima', 'tipe', 'model', 'created_at', 'updated_at'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['no_surat', 'penerima', 'tipe', 'model'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['no_surat', 'penerima', 'tipe', 'model', 'detail.nik', 'detail.nama', 'detail.jbtn', 'detail.departemen'];
    }

    /**
     * The relations that are used for including.
     * 
     * @return array
     * */
    public function includes(): array
    {
        return ['detail', 'detail.dep'];
    }
}
