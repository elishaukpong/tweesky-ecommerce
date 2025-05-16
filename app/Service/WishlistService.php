<?php

namespace App\Service;

use App\Exceptions\ProductAlreadyExistsInWishlist;
use App\Http\Filters\WishlistFilter;
use App\Models\Wishlist;
use App\Repositories\WishlistRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WishlistService
{
    public function __construct(private WishlistRepository $wishlistRepository)
    {
        //
    }

    public function getAll(WishlistFilter $filter): LengthAwarePaginator
    {
        return $this->wishlistRepository->paginateWithFilter($filter);
    }

    public function create(array $attributes): Wishlist
    {
        if ($this->wishlistRepository->exists(['user_id' => auth()->id(), 'product_id' => $attributes['product_id']])) {
            throw new ProductAlreadyExistsInWishlist;
        }

        $attributes['user_id'] = auth()->id();

        return $this->wishlistRepository->create($attributes);
    }

    public function update(Wishlist $wishlist, array $attributes): Wishlist
    {
        return $this->wishlistRepository->update($wishlist, $attributes);
    }

    public function delete(Wishlist $wishlist): void
    {
        $wishlist->delete();
    }
}
