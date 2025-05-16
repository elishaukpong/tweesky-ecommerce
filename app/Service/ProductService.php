<?php

namespace App\Service;

use App\Http\Filters\ProductFilter;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{

    public function __construct(private ProductRepository $productRepository)
    {
        //
    }

    public function getAll(ProductFilter $filter): LengthAwarePaginator
    {
        return $this->productRepository->paginateWithFilter($filter);
    }

    public function delete(Product $product): void
    {
        DB::transaction(function() use($product){
            $product->wishlists()->delete();

            $product->delete();
        });
    }

    public function update(Product $product, array $attributes): Product
    {
        return $this->productRepository->update($product, $attributes);
    }

    public function create(array $attributes): Product
    {
        $attributes['created_by'] = auth()->id();
        $attributes['slug'] = Str::slug($attributes['name']) . strtolower(Str::random(2));

        return $this->productRepository->create($attributes);
    }
}
