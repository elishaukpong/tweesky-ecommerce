<?php

namespace App\Traits;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait FilterableScope
{

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
