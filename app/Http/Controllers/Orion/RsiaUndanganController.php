<?php

namespace App\Http\Controllers\Orion;

use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Illuminate\Http\Request;

class RsiaUndanganController extends Controller
{
    use DisableAuthorization;

    /**
     * Fully-qualified model class name
     */
    protected $model = \App\Models\RsiaPenerimaUndangan::class;

    /**
     * @var string $resource
     */
    protected $resource = \App\Http\Resources\Undangan\UndanganResource::class;

    /**
     * @var string $collectionResource
     */
    protected $collectionResource = \App\Http\Resources\Undangan\UndanganCollection::class;


    /**
     * Builds Eloquent query for fetching entities in index method.
     *
     * @param Request $request
     * @param array $requestedRelations
     * @return Builder
     */
    protected function buildIndexFetchQuery(\Orion\Http\Requests\Request $request, array $requestedRelations): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);
        $query->select(['no_surat', 'model'])->groupBy('no_surat', 'model');
        
        return $query;
    }

    /**
     * Runs the given query for fetching entities in index method.
     *
     * @param Request $request
     * @param Builder $query
     * @param int $paginationLimit
     * @return Paginator|Collection
     * @throws BindingResolutionException
     */
    protected function runIndexFetchQuery(Request $request, \Illuminate\Database\Eloquent\Builder $query, int $paginationLimit)
    {
        $searchTerm = $request->input('search.value', '');
        $query      = $query->searchByRelatedModel($searchTerm);

        return $this->shouldPaginate($request, $paginationLimit) ? $query->paginate($paginationLimit) : $query->get();
    }

    /**
     * The list of available query scopes.
     *
     * @return array
     */
    public function exposedScopes(): array
    {
        return ['scopeSearchByRelatedModel'];
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
        return ['tipe', 'penerima'];
    }

    /**
     * The attributes that are used for sorting.
     *
     * @return array
     */
    public function sortableBy(): array
    {
        return ['no_surat', 'created_at', 'updated_at'];
    }

    /**
     * The relations and fields that are allowed to be aggregated on a resource.
     *
     * @return array
     */
    public function aggregates(): array
    {
        return [];
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
        return ['undangan'];
    }

    /**
     * The attributes that are used for searching.
     *
     * @return array
     */
    public function searchableBy(): array
    {
        return [];
    }
}
