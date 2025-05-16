<?php

use App\Casts\MoneyCast;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_create_product(): void
    {
        $productData = Product::factory()->make()->toArray();

        $product = Product::create($productData);

        $this->assertDatabaseHas(Product::class, [
            'id' => $product->id,
            'name' => $productData['name'],
            'price' => $productData['price'] * 100,
            'slug' => $productData['slug'],
            'stock_quantity' => $productData['stock_quantity'],
        ]);

        $this->assertCount(0, $product->wishlists);

    }

    #[Test]
    public function it_has_many_wishlists(): void
    {
        $product = Product::factory()->create();
        Wishlist::factory()->count(2)
            ->for($product)
            ->create();

        $this->assertInstanceOf(HasMany::class, $product->wishlists());
        $this->assertCount(2, $product->wishlists);
    }

    #[Test]
    public function it_belongs_to_created_by_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()
            ->for($user, 'createdBy')
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $product->createdBy());
        $this->assertTrue($product->createdBy->is($user));
    }

    #[Test]
    public function it_uses_slug_as_route_key(): void
    {
        $product = new Product();

        $this->assertSame('slug', $product->getRouteKeyName());
    }

    #[Test]
    public function it_casts_price_using_money_cast(): void
    {
        $product = new Product();

        $this->assertArrayHasKey('price', $product->getCasts());
        $this->assertSame(MoneyCast::class, $product->getCasts()['price']);
    }

    #[Test]
    public function it_uses_soft_deletes(): void
    {
        $product = Product::factory()->create();
        $product->delete();

        $this->assertSoftDeleted($product);
    }
}
