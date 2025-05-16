<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\WishlistFilter;
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
    public function store(StoreWishlistRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWishlistRequest $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        //
    }
}
