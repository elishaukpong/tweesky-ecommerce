<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{
    protected Builder $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    abstract protected function getModelClass(): Builder;

}
