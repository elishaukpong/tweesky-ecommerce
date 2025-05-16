<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Contracts\Filterable;
use App\Traits\FilterableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements Filterable
{
    use HasFactory, SoftDeletes, FilterableScope;

    protected $casts = [
        'price' => MoneyCast::class,
    ];

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
