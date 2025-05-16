<?php

namespace App\Http\Filters;

class WishlistFilter extends QueryFilter
{
    public function owner($value)
    {
        return $this->builder->where('user_id', $value);
    }

    public function name($value)
    {
        return $this->builder->whereHas('product', fn ($query) => $query->where('name', 'like', '%' . $value . '%'));
    }
}
