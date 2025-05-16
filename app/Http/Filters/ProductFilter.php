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

        if (count($priceRange) === 2 && is_numeric($priceRange[0]) && is_numeric($priceRange[1])) {
            return $this->builder->whereBetween('price', [
                $priceRange[0] * 100,
                $priceRange[1] * 100,
            ]);
        }

        if (is_numeric($priceRange[0])) {
            return $this->builder->where('price', $priceRange[0] * 100);
        }
    }
}
