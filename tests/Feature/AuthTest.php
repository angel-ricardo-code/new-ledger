<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->withoutAuth();
        parent::setUp();
    }

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'username' => 'newuser',
            'email' => 'user@test.com',
            'password' => 'Str0ng!pass',
            'password_confirmation' => 'Str0ng!pass',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['user', 'token']);
        $this->assertDatabaseHas('users', ['username' => 'newuser']);
    }

    public function test_register_validates_username_unique(): void
    {
        User::factory()->create(['username' => 'existing']);

        $response = $this->postJson('/api/register', [
            'username' => 'existing',
            'password' => 'Str0ng!pass',
            'password_confirmation' => 'Str0ng!pass',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['username']);
    }

    public function test_register_validates_weak_password(): void
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_returns_token(): void
    {
        $user = User::factory()->create([
            'username' => 'loginuser',
            'password' => bcrypt('Str0ng!pass'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'loginuser',
            'password' => 'Str0ng!pass',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'nonexistent',
            'password' => 'wrong',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['username']);
    }

    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/logout');

        $response->assertOk();
        $this->assertCount(0, $user->tokens);
    }

    public function test_user_endpoint_returns_authenticated_user(): void
    {
        $user = User::factory()->create(['username' => 'authcheck']);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertOk()
            ->assertJsonPath('username', 'authcheck');
    }

    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->getJson('/api/transactions');
        $response->assertUnauthorized();
    }

    public function test_public_routes_do_not_require_authentication(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'x',
            'password' => 'y',
        ]);
        $response->assertUnprocessable();
    }
}
