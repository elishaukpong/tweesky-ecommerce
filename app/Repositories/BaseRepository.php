<?php

namespace App\Repositories;

use App\Contracts\Filterable;
use App\Exceptions\ModelCannotBeFilteredException;
use App\Http\Filters\QueryFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Builder $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    abstract protected function getModelClass(): Builder;

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(Model $model, array $attributes): Model
    {
        $model->update($attributes);

        return $model->fresh();
    }

    public function first(string $column, mixed $value): Model
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    public function exists(array $attributes): bool
    {
        return $this->model->where($attributes)->exists();
    }

    public function paginateWithFilter(QueryFilter $filter, int $count = 15): LengthAwarePaginator
    {
        return $this->setFilter($filter)->paginate($count);
    }

    public function setFilter(QueryFilter $filter)
    {
        if(! $this->model->getModel() instanceof Filterable) {
            throw new ModelCannotBeFilteredException();
        }

        return $this->model->filter($filter);
    }
}
