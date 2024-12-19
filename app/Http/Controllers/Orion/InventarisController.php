<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;

class InventarisController extends \Orion\Http\Controllers\Controller
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
    protected $model = \App\Models\Inventaris::class;

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
        return [
            'no_inventaris',
            'tgl_pengadaan',
            'harga',
            'no_rak',
            'no_box'
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
            'no_inventaris',
            'kode_barang',
            'asal_barang',
            'tgl_pengadaan',
            'harga',
            'status_barang',
            'id_ruang',
            'no_rak',
            'no_box',
            'barang.thn_produksi',
            'ruang.nama_ruang'
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
            'no_inventaris',
            'kode_barang',
            'asal_barang',
            'status_barang',
            'id_ruang',
            'no_rak',
            'no_box',
            'barang.nama_barang',
            'barang.jml_barang',
        ];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return [
            'barang',
            'ruang',
            'gambar',
            'peminjaman',
            'pemeliharaan',
            'permintaan_perbaikan'
        ];
    }
}
