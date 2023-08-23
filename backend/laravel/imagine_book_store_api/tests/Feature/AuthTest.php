<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    const PREFIX = 'api/v1/';

    public function test_user_with_appropriate_data_can_register() {

            $data = [
                'name' => 'test',
                'email' => 'test@example.com',
                'password' => 'test-test',
            ];

            $response = $this->post(self::PREFIX.'register', $data);

            $response->assertStatus(200);
            $this->assertDatabaseCount('Users', 1);
    }

    public function test_user_with_inappropriate_data_cannot_register() {

        $data = [
            'name' => 'test',
            'email' => 'test',
            'password' => 'test-test',
        ];

        $response = $this->post(self::PREFIX.'register', $data);

        $response->assertStatus(422);
        $this->assertDatabaseCount('Users', 0);
    }

    public function test_user_with_appropriate_credentials_can_login() {

        $user = User::factory()->create();

        $response = $this->post(self::PREFIX.'login', [
            'email' => $user->email,
            'password' => 'password', // it's coming from User factory.
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token'
            ],
        ]);
    }

    public function test_user_with_inappropriate_credentials_cannot_login() {

        $user = User::factory()->create();

        $response = $this->post(self::PREFIX.'login', [
            'email' => $user->email,
            'password' => 'password123', // incorrect password.
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(403);
        $response->assertJsonMissing([
            'data' => [
                'token'
            ],
        ]);
    }

    public function test_logged_user_can_logout() {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/v1/logout');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [],
            'message',
        ]);
    }
}
