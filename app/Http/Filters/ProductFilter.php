<?php

namespace App\Http\Filters;

class ProductFilter extends QueryFilter
{
    public function name($value)
    {
        return $this->builder->where('name', 'like', '%' . $value . '%');
    }

    public function price($value)
    {
        $priceRange = array_map('trim', explode(',', $value));

        if (count($priceRange) > 1) {
            return $this->builder->whereBetween('price', [
                (int) $priceRange[0] * 100,
                (int) $priceRange[1] * 100,
            ]);
        }

        return $this->builder->where('price', (int) $priceRange[0] * 100);
    }
}
