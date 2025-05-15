<?php

namespace App\Repositories;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Builder;

class WishlistRepository extends BaseRepository
{

    protected function getModelClass(): Builder
    {
        return Wishlist::query();
    }
}
