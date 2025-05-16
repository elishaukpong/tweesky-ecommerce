<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_create_user(): void
    {
        $userData = User::factory()->make()->toArray();

        $userData['password'] = Hash::make('password');

        User::create($userData);

        $this->assertDatabaseHas(User::class, $userData);

    }

    #[Test]
    public function it_has_many_wishlists(): void
    {
        $user = User::factory()->create();
        Wishlist::factory()->count(3)
            ->for($user)
            ->create();

        $this->assertInstanceOf(HasMany::class, $user->wishlists());
        $this->assertCount(3, $user->wishlists);
    }

    #[Test]
    public function it_has_many_products(): void
    {
        $user = User::factory()->create();
        Product::factory()
            ->count(2)
            ->for($user, 'createdBy')
            ->create();

        $this->assertInstanceOf(HasMany::class, $user->products());
        $this->assertCount(2, $user->products);
    }
}
