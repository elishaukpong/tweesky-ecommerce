<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_login_with_valid_credentials()
    {
        $password = 'secure-password';

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'user@example.com',
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'token'
                    ]
                ]
            ])
            ->assertJsonPath('data.attributes.email', $user->email);;

    }

    #[Test]
    #[DataProvider('invalidLoginProvider')]
    public function login_validation_fails_with_invalid_data(array $invalidPayload, array $expectedErrors)
    {
        $response = $this->postJson(route('auth.login'), $invalidPayload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalidLoginProvider(): array
    {
        return [
            'missing email' => [
                ['password' => 'password123'],
                ['email']
            ],
            'missing password' => [['email' => 'user@example.com'], ['password']],
            'invalid email format' => [['email' => 'not-an-email', 'password' => 'password123'], ['email']],
        ];
    }

    #[Test]
    public function cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized()
            ->assertJson([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Invalid credentials.',
            ]);
    }
}

