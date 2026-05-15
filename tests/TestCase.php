<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private ?string $_authToken = null;
    private ?User $_authUser = null;
    private bool $_skipAuth = false;

    protected function setUp(): void
    {
        parent::setUp();
        if (!$this->_skipAuth) {
            $this->actingAs($this->authUser());
        }
    }

    protected function withoutAuth(): static
    {
        $this->_skipAuth = true;
        return $this;
    }

    protected function authUser(): User
    {
        if ($this->_authUser === null) {
            $this->_authUser = User::factory()->create(['username' => 'test_' . uniqid()]);
            $this->_authToken = $this->_authUser->createToken('test-token')->plainTextToken;
        }
        return $this->_authUser;
    }

    protected function authToken(): string
    {
        $this->authUser();
        return $this->_authToken;
    }

    public function json($method, $uri, array $data = [], array $headers = [], $options = 0)
    {
        if (!$this->_skipAuth && !str_starts_with($uri, '/api/register') && !str_starts_with($uri, '/api/login')) {
            $headers = array_merge($headers, [
                'Authorization' => 'Bearer ' . $this->authToken(),
            ]);
        }
        return parent::json($method, $uri, $data, $headers, $options);
    }
}
