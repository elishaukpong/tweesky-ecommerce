<?php

namespace App\Http\Filters;

class WishlistFilter extends QueryFilter
{
    public function owner($value)
    {
        return $this->builder->where('user_id', $value);
    }
}
