<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;

    protected Request $request;

    protected array $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->iterateAndCallMethod($this->request->all());

        return $builder;
    }

    public function iterateAndCallMethod(array $array): void
    {
        foreach ($array as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }
}

