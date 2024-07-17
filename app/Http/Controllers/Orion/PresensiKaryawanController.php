<?php

namespace App\Http\Controllers\Orion;

use Illuminate\Http\Request;
use Orion\Concerns\DisableAuthorization;

class PresensiKaryawanController extends \Orion\Http\Controllers\Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\RekapPresensi::class;

    /**
     * Name of the relationship as it is defined on the Post model
     */
    protected $relation = 'presensi';

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
     * Runs the given query for fetching relation entities in index method.
     *
     * @param Request $request
     * @param Relation $query
     * @param Model $parentEntity
     * @param int $paginationLimit
     * @return Paginator|Collection
     */
    // protected function runIndexFetchQuery(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Relations\Relation $query, \Illuminate\Database\Eloquent\Model $parentEntity, int $paginationLimit)
    // {
    //     return $this->shouldPaginate($request, $paginationLimit) ? $query->paginate($paginationLimit) : $query->get();
    // }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['id', 'shift', 'jam_datang', 'jam_pulang', 'status', 'keterlambatan', 'durasi', 'keterangan', 'photo'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return ['shift', 'jam_datang', 'jam_pulang', 'status'];
    }

    /**
     * The relations that are allowed to be included together with a resource.
     *
     * @return array
     */
    public function includes(): array
    {
        return [];
    }

    /**
     * The attributes that are used for filtering.
     *
     * @return array
     */
    public function filterableBy(): array
    {
        return ['id', 'shift', 'jam_datang', 'jam_pulang', 'status', 'keterlambatan', 'durasi', 'keterangan', 'photo'];
    }
}
