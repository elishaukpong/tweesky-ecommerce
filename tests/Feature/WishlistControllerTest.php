<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WishlistControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    #[Test]
    public function index_returns_only_authenticated_user_wishlists()
    {
        Wishlist::factory()
            ->for(auth()->user())
            ->count(3)
            ->create();

        Wishlist::factory()
            ->count(2)
            ->create();

        $response = $this->getJson(route('wishlists.index'));

        $response->assertOk()
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');

        foreach ($data as $wishlist) {
            $this->assertEquals(auth()->id(), $wishlist['relationships']['user']['id']);
        }
    }

    #[Test]
    public function can_create_wishlist_with_valid_data()
    {
        $product = Product::factory()->create();

        $payload = [
            'product_id' => $product->id,
            'note' => 'Wishlist note',
        ];

        $response = $this->postJson(route('wishlists.store'), $payload);

        $response->assertCreated()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'note',
                    ],
                    'relationships' => [
                        'user' => [],
                        'product' => []
                    ]
                ],
            ])
            ->assertJsonPath('data.attributes.note', 'Wishlist note');

        $this->assertDatabaseHas(Wishlist::class, [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'note' => 'Wishlist note',
        ]);
    }

    #[Test]
    #[DataProvider('storeValidationErrorProvider')]
    public function store_validation_errors(array $payload, string $errorField)
    {
        $response = $this->postJson(route('wishlists.store'), $payload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($errorField);
    }

    public static function storeValidationErrorProvider(): array
    {
        return [
            'missing product_id' => [['note' => 'Note only'], 'product_id'],
            'invalid product_id' => [['product_id' => 999999], 'product_id'],
            'note not string' => [['product_id' => 1, 'note' => 123], 'note'],
        ];
    }

    #[Test]
    public function can_show_wishlist()
    {
        $wishlist = Wishlist::factory()
            ->for(auth()->user())
            ->create();

        $this->getJson(route('wishlists.show', $wishlist))
            ->assertOk();
    }

    #[Test]
    public function can_update_wishlist()
    {
        $wishlist = Wishlist::factory()
            ->for(auth()->user())
            ->state(['note' => 'Old note'])
            ->create();

        $payload = ['note' => 'Updated note'];

        $response = $this->putJson(route('wishlists.update', $wishlist), $payload);

        $response->assertOk()
            ->assertJsonFragment([
                'note' => 'Updated note',
            ]);

        $this->assertDatabaseHas('wishlists', [
            'id' => $wishlist->id,
            'note' => 'Updated note',
        ]);
    }

    #[Test]
    public function can_destroy_wishlist()
    {
        $wishlist = Wishlist::factory()
            ->for(auth()->user())
            ->create();

        $this->deleteJson(route('wishlists.destroy', $wishlist))
            ->assertOk()
            ->assertJson([
                'status' => Response::HTTP_OK,
                'message' => __('Wishlist Removed'),
                'data' => [],
            ]);

        $this->assertSoftDeleted($wishlist);
    }

    #[Test]
    public function user_cannot_update_others_wishlist()
    {
        $wishlist = Wishlist::factory()->create();

        $this->putJson(route('wishlists.update', $wishlist), ['note' => 'New note'])
            ->assertUnauthorized();
    }

    #[Test]
    public function user_cannot_delete_others_wishlist()
    {
        $wishlist = Wishlist::factory()->create();

        $this->deleteJson(route('wishlists.destroy', $wishlist))
            ->assertUnauthorized();
    }

    #[Test]
    public function user_cannot_view_others_wishlist()
    {
        $wishlist = Wishlist::factory()->create();

        $this->getJson(route('wishlists.show', $wishlist))
            ->assertUnauthorized();
    }

    #[Test]
    public function user_can_filter_wishlists_by_product_name()
    {
        $matchingProduct = Product::factory()
            ->state(['name' => 'Gaming Laptop'])
            ->create();
        $nonMatchingProduct = Product::factory()
            ->state(['name' => 'Office Chair'])
            ->create();

        Wishlist::factory()
            ->for(auth()->user())
            ->for($matchingProduct)
            ->create();
        Wishlist::factory()
            ->for(auth()->user())
            ->for($nonMatchingProduct)
            ->create();

        $this->getJson(route('wishlists.index', ['name' => 'Laptop']))
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

}
