<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_create_wishlist(): void
    {
        $wishlistData = Wishlist::factory()->make()->toArray();

        Wishlist::create($wishlistData);

        $this->assertDatabaseHas(Wishlist::class, $wishlistData);

    }

    #[Test]
    public function it_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $wishlist = Wishlist::factory()
            ->for($user)
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $wishlist->user());
        $this->assertInstanceOf(User::class, $wishlist->user()->first());
        $this->assertEquals($user->id, $wishlist->user->id);
    }

    #[Test]
    public function it_belongs_to_a_product(): void
    {
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()
            ->for($product)
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $wishlist->product());
        $this->assertInstanceOf(Product::class, $wishlist->product()->first());
        $this->assertEquals($product->id, $wishlist->product->id);
    }

    #[Test]
    public function it_uses_soft_deletes(): void
    {
        $wishlist = Wishlist::factory()->create();
        $wishlist->delete();

        $this->assertSoftDeleted($wishlist);
    }
}
