<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $data = [
            'name' => 'Elisha Ukpong',
            'email' => 'elisha@example.com',
            'password' => 'securepassword',
            'password_confirmation' => 'securepassword',
        ];

        $response = $this->postJson(route('auth.register'), $data);

        $response->assertCreated()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'token',
                    ],
                ],
            ])
            ->assertJsonPath('data.attributes.email', $data['email'])
            ->assertJsonPath('data.attributes.name', $data['name']);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function test_registration_fails_if_email_is_already_taken()
    {
        User::factory()->create([
            'email' => 'taken@example.com',
        ]);

        $response = $this->postJson(route('auth.register'), [
            'name' => 'Another User',
            'email' => 'taken@example.com',
            'password' => 'anotherpassword',
            'password_confirmation' => 'anotherpassword',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    #[DataProvider('invalidRegistrationDataProvider')]
    public function test_registration_validation_fails(array $payload, array $expectedErrors)
    {
        $response = $this->postJson(route('auth.register'), $payload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalidRegistrationDataProvider(): array
    {
        return [
            'missing all fields' => [
                [],
                ['name', 'email', 'password'],
            ],
            'missing name' => [
                [
                    'email' => 'user@example.com',
                    'password' => 'secret1234',
                    'password_confirmation' => 'secret1234',
                ],
                ['name'],
            ],
            'missing email' => [
                [
                    'name' => 'User',
                    'password' => 'secret1234',
                    'password_confirmation' => 'secret1234',
                ],
                ['email'],
            ],
            'missing password' => [
                [
                    'name' => 'User',
                    'email' => 'user@example.com',
                ],
                ['password'],
            ],
            'password not confirmed' => [
                [
                    'name' => 'User',
                    'email' => 'user@example.com',
                    'password' => 'secret1234',
                ],
                ['password'],
            ],
            'invalid email format' => [
                [
                    'name' => 'User',
                    'email' => 'invalid-email',
                    'password' => 'secret1234',
                    'password_confirmation' => 'secret1234',
                ],
                ['email'],
            ],
            'password too short' => [
                [
                    'name' => 'User',
                    'email' => 'user@example.com',
                    'password' => 'short',
                    'password_confirmation' => 'short',
                ],
                ['password'],
            ],
        ];
    }
}
