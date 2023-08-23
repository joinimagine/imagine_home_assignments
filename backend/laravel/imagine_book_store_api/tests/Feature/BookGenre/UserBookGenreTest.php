<?php

namespace Tests\Feature;

use App\Models\BookGenre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBookGenreTest extends TestCase
{

    use RefreshDatabase;

    const ROUTE = 'api/v1/admin/book-genres';
    const TABLE = 'book_genres';


    public function test_user_cannot_access_book_genres() {

        BookGenre::factory(10)->create();

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::ROUTE);
        $response->assertStatus(401);
        $response->assertJsonMissing(['data']);
    }

    protected function createUser() {

        $userRole = Role::create(['name' => 'user']);

        $user = User::factory()->create();
        $user->attachRole($userRole->name);

        return $user;
    }
}

