<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    #[Test]
    public function can_list_products()
    {
        Product::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('products.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'description',
                            'price',
                            'stock_quantity',
                        ],
                        'relationships' => [
                            'createdBy' => []
                        ]
                    ]
                ]
            ]);
    }

    #[Test]
    public function can_store_product()
    {
        $payload = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 500,
            'stock_quantity' => 20,
        ];

        $response = $this->postJson(route('products.store'), $payload);

        $response->assertCreated()
            ->assertJsonFragment([
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 500,
            ]);

        $payload['price'] = $payload['price'] * 100;

        $this->assertDatabaseHas(Product::class, $payload);
    }

    #[Test]
    public function can_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', $product));

        $response->assertOk()
            ->assertJsonFragment([
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
            ]);
    }

    #[Test]
    public function can_update_product()
    {
        $user = auth()->user();
        $product = Product::factory()
            ->for($user, 'createdBy')
            ->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $oldName = $product->name,
            'created_by' => $user->id
        ]);

        $payload = ['name' => 'Updated Product'];

        $response = $this->putJson(route('products.update', $product), $payload);

        $response->assertOk()
            ->assertJsonFragment($payload);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'created_by' => $user->id
        ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'name' => $oldName,
            'created_by' => $user->id
        ]);
    }

    #[Test]
    public function can_delete_product()
    {
        $user = auth()->user();
        $product = Product::factory()
            ->for($user, 'createdBy')
            ->create();

        $response = $this->deleteJson(route('products.destroy', $product));

        $response->assertOk();

        $this->assertSoftDeleted($product);
    }

    #[Test]
    public function user_cannot_update_product_they_didnt_create()
    {
        $product = Product::factory()->create();

        $response = $this->putJson(route('products.update', $product), [
            'name' => 'Hacked Update'
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function user_cannot_delete_product_they_didnt_create()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product));

        $response->assertUnauthorized();
    }

   #[Test]
    public function it_can_filter_products_by_name()
    {
        Product::factory()
            ->state(['name' => 'Apple iPhone'])
            ->create();
        Product::factory()
            ->state(['name' => 'Samsung Galaxy'])
            ->create();
        Product::factory()
            ->state(['name' => 'Nokia Brick'])
            ->create();

        $response = $this->getJson(route('products.index', ['name' => 'apple']));

        $response->assertOk()
            ->assertJsonCount(1, 'data');

        $this->assertEquals('Apple iPhone', $response->json('data.0.attributes.name'));
    }

    #[Test]
    public function it_can_filter_products_by_exact_price()
    {
        Product::factory()
            ->state(['name' => 'Cheap Phone', 'price' => 15])
            ->create();
        Product::factory()
            ->state(['name' => 'Expensive Phone', 'price' => 50])
            ->create();

        $response = $this->getJson(route('products.index', ['price' => '15']))
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->assertEquals('Cheap Phone', $response->json('data.0.attributes.name'));
    }

    #[Test]
    public function it_can_filter_products_by_price_range()
    {
        Product::factory()->create(['name' => 'Budget Phone', 'price' => 10]);
        Product::factory()->create(['name' => 'Midrange Phone', 'price' => 30]);
        Product::factory()->create(['name' => 'Flagship Phone', 'price' => 70]);

        $response = $this->getJson(route('products.index', ['price' => '20,50']));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $this->assertEquals('Midrange Phone', $response->json('data.0.attributes.name'));
    }

    #[Test]
    public function it_ignores_invalid_price_filter_format()
    {
        Product::factory()->create(['name' => 'Some Phone', 'price' => 25]);

        $response = $this->getJson(route('products.index', ['filter[price]' => 'not,a,price']));

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
