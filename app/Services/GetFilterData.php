<?php

namespace App\Services;

use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Orion\Drivers\Standard\SearchBuilder;
use Orion\Drivers\Standard\ParamsValidator;
use Orion\Drivers\Standard\RelationsResolver;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

class GetFilterData
{
    private $model;

    private $request;

    private $actionMethod;


    private $resourceModelClass;

    private $paramsValidator;

    private $relationsResolver;

    private $searchBuilder;

    public function __construct(Model $model, Request $request, string $actionMethod)
    {
        $this->model = $model;
        $this->request = $request;
        $this->actionMethod = $actionMethod;

        $this->resourceModelClass = get_class($model);
        $this->paramsValidator = new ParamsValidator(
            $model->exposedScopes(),
            $model->filterableBy(),
            $model->sortableBy(),
            $model->aggregatableBy(),
            $model->includableBy()
        );
        $this->relationsResolver = new RelationsResolver([], []);
        $this->searchBuilder = new SearchBuilder($model->searchableBy());
    }

    public function apply()
    {
        $query = $this->model->newQuery();

        if (in_array($this->actionMethod, ['index', 'search', 'show'])) {
            if ($this->actionMethod === 'search') {
                $this->applyScopesToQuery($query, $this->request);
                $this->applyFiltersToQuery($query, $this->request);
                $this->applySearchingToQuery($query, $this->request);
                $this->applySortingToQuery($query, $this->request);
            }
            $this->applySoftDeletesToQuery($query, $this->request);
        }

        $this->applyIncludesToQuery($query, $this->request);
        $this->applyAggregatesToQuery($query, $this->request);

        return $query;
    }

    public function applyScopesToQuery($query, Request $request)
    {
        $this->paramsValidator->validateScopes($request);

        $scopeDescriptors = $request->get('scopes', []);

        foreach ($scopeDescriptors as $scopeDescriptor) {
            $query->{$scopeDescriptor['name']}(...\Illuminate\Support\Arr::get($scopeDescriptor, 'parameters', []));
        }
    }

    public function applyFiltersToQuery($query, Request $request, array $filterDescriptors = []): void
    {
        $this->paramsValidator->validateFilters($request);

        $filterDescriptors = $request->get('filters', []);

        foreach ($filterDescriptors as $filterDescriptor) {
            $or = \Illuminate\Support\Arr::get($filterDescriptor, 'type', 'and') === 'or';

            if (is_array($childrenDescriptors = \Illuminate\Support\Arr::get($filterDescriptor, 'nested'))) {
                $query->{$or ? 'orWhere' : 'where'}(function ($query) use ($request, $childrenDescriptors) {
                    $this->applyFiltersToQuery($query, $request, $childrenDescriptors);
                });
            } elseif (strpos($filterDescriptor['field'], '.') !== false) {
                $relation = $this->relationsResolver->relationFromParamConstraint($filterDescriptor['field']);
                $relationField = $this->relationsResolver->relationFieldFromParamConstraint($filterDescriptor['field']);

                if ($relation === 'pivot') {
                    $this->buildPivotFilterQueryWhereClause($relationField, $filterDescriptor, $query, $or);
                } else {
                    $relationInstance = $this->relationsResolver->relationInstanceFromParamConstraint($this->resourceModelClass, $filterDescriptor['field']);
                    $qualifiedRelationFieldName = $this->relationsResolver->getQualifiedRelationFieldName($relationInstance, $relationField);

                    $query->{$or ? 'orWhereHas' : 'whereHas'}(
                        $relation,
                        function ($relationQuery) use ($qualifiedRelationFieldName, $filterDescriptor) {
                            $this->buildFilterQueryWhereClause($qualifiedRelationFieldName, $filterDescriptor, $relationQuery);
                        }
                    );
                }
            } else {
                $this->buildFilterQueryWhereClause(
                    $this->getQualifiedFieldName($filterDescriptor['field']),
                    $filterDescriptor,
                    $query,
                    $or
                );
            }
        }
    }

    public function applySearchingToQuery($query, Request $request): void
    {
        if (!$requestedSearchDescriptor = $request->get('search')) {
            return;
        }

        $this->paramsValidator->validateSearch($request);

        $searchables = $this->searchBuilder->searchableBy();

        $query->where(
            function ($whereQuery) use ($searchables, $requestedSearchDescriptor) {
                $requestedSearchString = $requestedSearchDescriptor['value'];

                $caseSensitive = (bool) \Illuminate\Support\Arr::get(
                    $requestedSearchDescriptor,
                    'case_sensitive',
                    config('orion.search.case_sensitive')
                );

                /**
                 * @var Builder $whereQuery
                 */
                foreach ($searchables as $searchable) {
                    if (strpos($searchable, '.') !== false) {
                        $relation = $this->relationsResolver->relationFromParamConstraint($searchable);
                        $relationField = $this->relationsResolver->relationFieldFromParamConstraint($searchable);

                        $relationInstance = (new $this->resourceModelClass)->{$relation}();

                        $qualifiedRelationFieldName = $this->relationsResolver->getQualifiedRelationFieldName($relationInstance, $relationField);

                        $whereQuery->orWhereHas(
                            $relation,
                            function ($relationQuery) use ($qualifiedRelationFieldName, $requestedSearchString, $caseSensitive) {
                                /**
                                 * @var Builder $relationQuery
                                 */
                                if (!$caseSensitive) {
                                    return $relationQuery->whereRaw(
                                        "lower({$qualifiedRelationFieldName}) like lower(?)",
                                        ['%' . $requestedSearchString . '%']
                                    );
                                }

                                return $relationQuery->where(
                                    $qualifiedRelationFieldName,
                                    'like',
                                    '%' . $requestedSearchString . '%'
                                );
                            }
                        );
                    } else {
                        $qualifiedFieldName = $this->getQualifiedFieldName($searchable);

                        if (!$caseSensitive) {
                            $whereQuery->orWhereRaw(
                                "lower({$qualifiedFieldName}) like lower(?)",
                                ['%' . $requestedSearchString . '%']
                            );
                        } else {
                            $whereQuery->orWhere(
                                $qualifiedFieldName,
                                'like',
                                '%' . $requestedSearchString . '%'
                            );
                        }
                    }
                }
            }
        );
    }

    public function applySortingToQuery($query, Request $request): void
    {
        $this->paramsValidator->validateSort($request);
        $sortableDescriptors = $request->get('sort', []);

        foreach ($sortableDescriptors as $sortable) {
            $sortableField = $sortable['field'];
            $direction = \Illuminate\Support\Arr::get($sortable, 'direction', 'asc');

            if (strpos($sortableField, '.') !== false) {
                $relation = $this->relationsResolver->relationFromParamConstraint($sortableField);
                $relationField = $this->relationsResolver->relationFieldFromParamConstraint($sortableField);

                if ($relation === 'pivot') {
                    $query->orderByPivot($relationField, $direction);
                    continue;
                }

                /**
                 * @var Relation $relationInstance
                 */
                $relationInstance = (new $this->resourceModelClass)->{$relation}();

                if ($relationInstance instanceof MorphTo) {
                    continue;
                }

                $relationTable = $this->relationsResolver->relationTableFromRelationInstance($relationInstance);
                $relationForeignKey = $this->relationsResolver->relationForeignKeyFromRelationInstance(
                    $relationInstance
                );
                $relationLocalKey = $this->relationsResolver->relationLocalKeyFromRelationInstance($relationInstance);

                $requiresJoin = collect($query->toBase()->joins ?? [])
                    ->where('table', $relationTable)->isEmpty();

                if ($requiresJoin) {
                    $query->leftJoin($relationTable, $relationForeignKey, '=', $relationLocalKey);
                }

                $qualifiedRelationFieldName = $this->relationsResolver->getQualifiedRelationFieldName($relationInstance,  $relationField);

                $query->orderBy($qualifiedRelationFieldName, $direction)
                    ->select($this->getQualifiedFieldName('*'));
            } else {
                $query->orderBy($this->getQualifiedFieldName($sortableField), $direction);
            }
        }
    }

    public function applySoftDeletesToQuery($query, Request $request): bool
    {
        if (!$query->getMacro('withTrashed')) {
            return false;
        }

        if (filter_var($request->query('with_trashed', false), FILTER_VALIDATE_BOOLEAN)) {
            $query->withTrashed();
        } elseif (filter_var($request->query('only_trashed', false), FILTER_VALIDATE_BOOLEAN)) {
            $query->onlyTrashed();
        }

        return true;
    }

    public function applyIncludesToQuery($query, Request $request, array $includeDescriptors = []): void
    {
        if (!$includeDescriptors) {
            $this->paramsValidator->validateIncludes($request);

            $requestedIncludeDescriptors = collect($request->get('includes', []));

            $includeDescriptors = collect($this->relationsResolver->requestedRelations($request))
                ->map(function ($include) use ($requestedIncludeDescriptors) {
                    $requestedIncludeDescriptor = $requestedIncludeDescriptors
                        ->where('relation', $include)
                        ->first();

                    return $requestedIncludeDescriptor ?? ['relation' => $include];
                })->toArray();
        }

        foreach ($includeDescriptors as $includeDescriptor) {
            if (!$relationModelClass = $this->getRelationModelClass($includeDescriptor['relation'])) {
                continue;
            }

            if ($relationModelClass === MorphTo::class) {
                $query->with($includeDescriptor['relation']);

                continue;
            }

            $query->with([
                $includeDescriptor['relation'] => function (Relation $includeQuery) use (
                    $includeDescriptor,
                    $request,
                    $relationModelClass
                ) {
                    $relationQueryBuilder = new self(new $relationModelClass, $request, $this->actionMethod);

                    if (array_key_exists("limit", $includeDescriptor)) {
                        $includeQuery->take($includeDescriptor["limit"]);
                    }

                    $relationQueryBuilder->applyFiltersToQuery(
                        $includeQuery,
                        $request,
                        $this->removeFieldPrefixFromFields(
                            $includeDescriptor['filters'] ?? [],
                            $includeDescriptor['relation'] . '.'
                        )
                    );
                },
            ]);
        }
    }

    public function applyAggregatesToQuery($query, Request $request, array $aggregateDescriptors = []): void
    {
        if (!$aggregateDescriptors) {
            $this->paramsValidator->validateAggregators($request);

            $aggregateDescriptors = collect();
            // Here we regroup query and post params on the same format
            foreach (['count', 'min', 'max', 'avg', 'sum', 'exists'] as $aggregateFunction) {
                $aggregateDescriptors = $aggregateDescriptors->merge(
                    collect(explode(',', $request->query("with_$aggregateFunction", '')))
                        ->filter()
                        ->map(function ($include) use ($aggregateFunction) {
                            $explodedInclude = explode('.', $include);
                            return [
                                'relation' => $explodedInclude[0],
                                'field' => $explodedInclude[1] ?? '*',
                                'type' => $aggregateFunction,
                            ];
                        })->all()
                );
            }

            $aggregateDescriptors = $aggregateDescriptors->merge($request->get('aggregates', []));
        }

        foreach ($aggregateDescriptors as $aggregateDescriptor) {
            if ((float) app()->version() < 8.0) {
                throw new \RuntimeException(
                    "Aggregate queries are only supported with Laravel 8 and later"
                );
            }

            if (!$relationModelClass = $this->getRelationModelClass($aggregateDescriptor['relation'])) {
                continue;
            }

            if ($relationModelClass === MorphTo::class) {
                $query->withAggregate(
                    $aggregateDescriptor['relation'],
                    $aggregateDescriptor['field'] ?? '*',
                    $aggregateDescriptor['type']
                );

                continue;
            }

            $query->withAggregate([
                $aggregateDescriptor['relation'] => function (Builder $aggregateQuery) use (
                    $aggregateDescriptor,
                    $request,
                    $relationModelClass
                ) {
                    $relationQueryBuilder = new self(new $relationModelClass, $request, $this->actionMethod);

                    $relationQueryBuilder->applyFiltersToQuery(
                        $aggregateQuery,
                        $request,
                        $this->removeFieldPrefixFromFields(
                            $aggregateDescriptor['filters'] ?? [],
                            $aggregateDescriptor['relation'] . '.'
                        )
                    );
                },
            ], $aggregateDescriptor['field'] ?? '*', $aggregateDescriptor['type']);
        }
    }






    protected function removeFieldPrefixFromFields(array $array, string $search)
    {
        return collect($array)
            ->transform(function ($item) use ($search) {
                if (isset($item['nested'])) {
                    $item['nested'] = $this->removeFieldPrefixFromFields($item['nested'], $search);
                } else {
                    $item['field'] = \Illuminate\Support\Str::replaceFirst($search, '', $item['field']);
                }

                return $item;
            })
            ->all();
    }

    public function getRelationModelClass(string $relation): ?string
    {
        $relations = collect(explode('.', $relation));

        $resourceModel = (new $this->resourceModelClass);

        foreach ($relations as $nestedRelation) {
            if ((float) app()->version() >= 8.0) {
                if (!$resourceModel->isRelation($nestedRelation)) {
                    return null;
                }
            } elseif (!method_exists($resourceModel, $nestedRelation)) {
                return null;
            }

            if ($resourceModel->$nestedRelation() instanceof MorphTo) {
                return MorphTo::class;
            }

            $resourceModel = $resourceModel->$nestedRelation()->getModel();
        }

        return get_class($resourceModel);
    }

    public function getQualifiedFieldName(string $field): string
    {
        $table = (new $this->resourceModelClass)->getTable();

        return "{$table}.{$field}";
    }

    protected function buildFilterQueryWhereClause(string $field, array $filterDescriptor, $query, bool $or = false)
    {
        if (is_array($filterDescriptor['value']) && in_array(null, $filterDescriptor['value'], true)) {
            $query = $query->{$or ? 'orWhereNull' : 'whereNull'}($field);

            $filterDescriptor['value'] = collect($filterDescriptor['value'])->filter()->values()->toArray();

            if (!count($filterDescriptor['value'])) {
                return $query;
            }
        }

        return $this->buildFilterNestedQueryWhereClause($field, $filterDescriptor, $query, $or);
    }

    protected function buildFilterNestedQueryWhereClause(
        string $field,
        array $filterDescriptor,
        $query,
        bool $or = false
    ) {
        /** @var Model $resourceModel */
        $resourceModel = (new $this->resourceModelClass);

        $dateCasts = collect($resourceModel->getCasts())->filter(function (string $type) {
            return in_array($type, ['date', 'datetime']);
        })->keys()->toArray();

        $dateFields = array_merge($resourceModel->getDates(), $dateCasts);

        $treatAsDateField = $filterDescriptor['value'] !== null &&
            in_array($filterDescriptor['field'], $dateFields, true);

        if ($treatAsDateField && \Carbon\Carbon::parse($filterDescriptor['value'])->toTimeString() === '00:00:00') {
            $constraint = 'whereDate';
        } elseif (in_array(\Illuminate\Support\Arr::get($filterDescriptor, 'operator'), ['all in', 'any in'])) {
            $constraint = 'whereJsonContains';
        } else {
            $constraint = 'where';
        }

        if ($constraint !== 'whereJsonContains' && (!is_array(
            $filterDescriptor['value']
        ) || $constraint === 'whereDate')) {
            $query->{$or ? 'or' . ucfirst($constraint) : $constraint}(
                $field,
                $filterDescriptor['operator'] ?? '=',
                $filterDescriptor['value']
            );
        } elseif ($constraint === 'whereJsonContains') {
            if (!is_array($filterDescriptor['value'])) {
                $query->{$or ? 'orWhereJsonContains' : 'whereJsonContains'}(
                    $field,
                    $filterDescriptor['value']
                );
            } else {
                $query->{$or ? 'orWhere' : 'where'}(function ($nestedQuery) use ($filterDescriptor, $field) {
                    foreach ($filterDescriptor['value'] as $value) {
                        $nestedQuery->{$filterDescriptor['operator'] === 'any in' ? 'orWhereJsonContains' : 'whereJsonContains'}(
                            $field,
                            $value
                        );
                    }
                });
            }
        } else {
            $query->{$or ? 'orWhereIn' : 'whereIn'}(
                $field,
                $filterDescriptor['value'],
                'and',
                $filterDescriptor['operator'] === 'not in'
            );
        }

        return $query;
    }

    protected function buildPivotFilterQueryWhereClause(
        string $field,
        array $filterDescriptor,
        $query,
        bool $or = false
    ) {
        if (is_array($filterDescriptor['value']) && in_array(null, $filterDescriptor['value'], true)) {
            if ((float) app()->version() <= 7.0) {
                throw new \RuntimeException(
                    "Filtering by nullable pivot fields is only supported for Laravel version > 8.0"
                );
            }

            $query = $query->{$or ? 'orWherePivotNull' : 'wherePivotNull'}($field);

            $filterDescriptor['value'] = collect($filterDescriptor['value'])->filter()->values()->toArray();

            if (!count($filterDescriptor['value'])) {
                return $query;
            }
        }

        return $this->buildPivotFilterNestedQueryWhereClause($field, $filterDescriptor, $query);
    }

    protected function buildPivotFilterNestedQueryWhereClause(
        string $field,
        array $filterDescriptor,
        $query,
        bool $or = false
    ) {
        $pivotClass = $query->getPivotClass();
        $pivot = new $pivotClass;

        $treatAsDateField = $filterDescriptor['value'] !== null && in_array($field, $pivot->getDates(), true);

        if ($treatAsDateField && \Carbon\Carbon::parse($filterDescriptor['value'])->toTimeString() === '00:00:00') {
            $query->addNestedWhereQuery(
                $query->newPivotStatement()->whereDate(
                    $query->getTable() . ".{$field}",
                    $filterDescriptor['operator'] ?? '=',
                    $filterDescriptor['value']
                )
            );
        } elseif (!is_array($filterDescriptor['value'])) {
            $query->{$or ? 'orWherePivot' : 'wherePivot'}(
                $field,
                $filterDescriptor['operator'] ?? '=',
                $filterDescriptor['value']
            );
        } else {
            $query->{$or ? 'orWherePivotIn' : 'wherePivotIn'}(
                $field,
                $filterDescriptor['value'],
                'and',
                $filterDescriptor['operator'] === 'not in'
            );
        }

        return $query;
    }
}
