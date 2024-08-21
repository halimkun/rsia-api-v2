<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CutiPegawaiController extends \Orion\Http\Controllers\RelationController
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
    protected $model = \App\Models\Pegawai::class;

    /**
     * Name of the relationship as it is defined on the Post model
     */
    protected $relation = 'cuti';

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
        return ['id_cuti', 'nama', 'nik', 'id_pegawai', 'dep_id', 'tanggal_cuti', 'jenis', 'status', 'tanggal_pengajuan'];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['id_cuti', 'nama', 'nik', 'id_pegawai', 'dep_id', 'tanggal_cuti', 'jenis', 'status', 'tanggal_pengajuan'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['nama', 'nik', 'id_pegawai', 'dep_id', 'tanggal_cuti', 'jenis', 'status', 'tanggal_pengajuan'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return ['pegawai', 'pegawaiNik', 'departemen'];
    }

    /**
     * The relations that are allowed to be always included together with a resource.
     * 
     * @return array
     * */
    public function alwaysIncludes(): array
    {
        return [];
    }
}
