<?php

namespace App\Service;

use App\Http\Filters\WishlistFilter;
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
        return $this->wishlistRepository->paginateWithFilter($filter,2);
    }
}
