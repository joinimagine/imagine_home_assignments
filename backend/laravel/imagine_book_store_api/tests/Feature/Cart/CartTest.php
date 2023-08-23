<?php

namespace Tests\Feature\Cart;

use App\Models\Book;
use App\Models\BookGenre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE = 'api/v1/cart';
    const TABLE = 'carts';

    public function test_user_can_view_his_cart() {

        $bookGenre = $this->createBookGenre();
        $book = $this->createBook($bookGenre->id, 10);

        $user = $this->createUser();

        $user->cart()->attach($book->id, ['quantity' => 5]);

        $response = $this->actingAs($user)->get(self::ROUTE);

        $response->assertStatus(200);
        $this->assertCount(1, $user->cart()->get());
        $this->assertCount(1, $response->json()['data']);
    }

    public function test_user_can_add_book_with_enough_quantity_to_his_cart() {

        $bookGenre = $this->createBookGenre();
        $book = $this->createBook($bookGenre->id, 10);

        $user = $this->createUser();

        $response = $this->actingAs($user)->post(self::ROUTE, [
            'book_id' => $book->id,
            'quantity' => 5
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('carts', 1);
        $this->assertCount(1, $user->cart()->get());
    }

    public function test_user_cannot_add_book_with_not_enough_quantity_to_his_cart() {

        $bookGenre = $this->createBookGenre();
        $book = $this->createBook($bookGenre->id, 10);

        $user = $this->createUser();

        $response = $this->actingAs($user)->post(self::ROUTE, [
            'book_id' => $book->id,
            'quantity' => 25
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('carts', 0);
        $this->assertCount(0, $user->cart()->get());
    }

    public function test_book_quantity_not_affected_when_added_to_users_cart() {

        $bookGenre = $this->createBookGenre();
        $book = $this->createBook($bookGenre->id, 10);

        $user = $this->createUser();

        $response = $this->actingAs($user)->post(self::ROUTE, [
            'book_id' => $book->id,
            'quantity' => 5
        ]);

        $response->assertStatus(200);
        $this->assertEquals(10, $book->quantity);
    }


    /* Data Seeding Helpers */

    protected function createBookGenre() {

        return BookGenre::factory()->create();
    }

    protected function createBook($bookGenreId, $quantity) {

        return Book::factory()->create(['book_genre_id' => $bookGenreId, 'quantity' => $quantity]);
    }

    protected function createUser() {

        $userRole = Role::create(['name' => 'user']);

        $user = User::factory()->create();
        $user->attachRole($userRole->name);

        return $user;
    }

}
