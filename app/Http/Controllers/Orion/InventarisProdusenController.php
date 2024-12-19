<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;

class InventarisProdusenController extends \Orion\Http\Controllers\Controller
{
    /**
     * Disable authorization for all actions
     * 
     * @var bool
     * */
    use \Orion\Concerns\DisableAuthorization;

    /**
     * Model class for Inventaris
     * 
     * @var string
     * */
    protected $model = \App\Models\InventarisProdusen::class;

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
        return ['nama_produsen'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return [];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['nama_produsen'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return ['barang'];
    }
}
