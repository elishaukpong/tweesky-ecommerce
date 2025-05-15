<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository extends BaseRepository
{

    protected function getModelClass(): Builder
    {
        return Product::query();
    }
}
