<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Http\Requests\Product\DeleteProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Service\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductFilter $filter): JsonResponse
    {
        $products = $this->productService->getAll($filter);

        return $this->ok(__('Products Retrieved'), ProductResource::collection($products));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return $this->created(__('Products Created'), ProductResource::make($product));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return $this->ok(__('Products Retrieved'), ProductResource::make($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->update($product, $request->validated());

        return $this->ok(__('Products Updated'), ProductResource::make($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteProductRequest $request, Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return $this->ok(__('Product Deleted'));
    }
}
