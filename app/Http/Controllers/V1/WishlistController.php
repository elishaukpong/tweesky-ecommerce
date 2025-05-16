<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\WishlistFilter;
use App\Http\Requests\Wishlist\DeleteWishlistRequest;
use App\Http\Requests\Wishlist\ShowWishlistRequest;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Http\Requests\Wishlist\UpdateWishlistRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use App\Service\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(protected WishlistService $wishlistService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WishlistFilter $filter): JsonResponse
    {
        $request->offsetSet('owner', auth()->id());

        $wishlists = $this->wishlistService->getAll($filter);

        return $this->ok(__('Wishlists Retrieved'), WishlistResource::collection($wishlists));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWishlistRequest $request): JsonResponse
    {
        $wishlist = $this->wishlistService->create($request->validated());

        return $this->created(__('Wishlist Created'), WishlistResource::make($wishlist));
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowWishlistRequest $request, Wishlist $wishlist): JsonResponse
    {
        return $this->ok(__('Wishlist retrieved'), WishlistResource::make($wishlist));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWishlistRequest $request, Wishlist $wishlist): JsonResponse
    {
        $product = $this->wishlistService->update($wishlist, $request->validated());

        return $this->ok(__('Wishlist Updated'), WishlistResource::make($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteWishlistRequest $request, Wishlist $wishlist): JsonResponse
    {
        $this->wishlistService->delete($wishlist);

        return $this->ok(__('Wishlist Removed'));
    }
}
