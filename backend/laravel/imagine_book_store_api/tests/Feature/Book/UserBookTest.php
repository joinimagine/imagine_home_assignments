<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookGenre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBookTest extends TestCase
{
    use RefreshDatabase;

    const USER_ROUTE = 'api/v1/books';
    const TABLE = 'books';

    public function test_unauthenticated_users_cannot_access_books() {

        $bookGenre = BookGenre::factory()->create();
        Book::factory()->create(['quantity' => 5, 'book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['quantity' => 0, 'book_genre_id' => $bookGenre->id]);

        $userRoutResponse = $this->get(self::USER_ROUTE);

        $userRoutResponse->assertStatus(401);

        $userRoutResponse->assertJsonMissing(['data']);
    }

    public function test_users_can_only_view_books_available_to_order() {

        $bookGenre = BookGenre::factory()->create();
        $availableToOrderBook = Book::factory()->create(['quantity' => 5, 'book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['quantity' => 0, 'book_genre_id' => $bookGenre->id]);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::USER_ROUTE);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
        $this->assertEquals($availableToOrderBook->id, $response->json()['data'][0]['id']);
    }

    public function test_users_can_access_books_with_accurate_pagination() {

        $totalBooks = 10;
        $perPage = 5;

        $bookGenre = BookGenre::factory()->create();
        Book::factory($totalBooks)->create(['book_genre_id' => $bookGenre->id, 'quantity' => 1]);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::USER_ROUTE . '?perPage=' . $perPage);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount($perPage, $responseData['data']);
        $this->assertEquals(1, $responseData['meta']['current_page']);
        $this->assertEquals(($totalBooks / $perPage), $responseData['meta']['last_page']);
        $this->assertEquals($totalBooks, $responseData['meta']['total']);
        $this->assertEquals($perPage, $responseData['meta']['per_page']);
    }

    public function test_users_can_filter_books_based_on_title() {


        $bookGenre = BookGenre::factory()->create();
        $firstBook = Book::factory()->create(['title' => 'first-book', 'quantity' => 1, 'book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['title' => 'second-book', 'quantity' => 1, 'book_genre_id' => $bookGenre->id]);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::USER_ROUTE . '?filter[title]=' . 'first');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($firstBook->id, $responseData['data'][0]['id']);
    }

    public function test_users_can_filter_books_based_on_author() {


        $bookGenre = BookGenre::factory()->create();
        $firstBook = Book::factory()->create(['author' => 'first-author', 'quantity' => 1, 'book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['author' => 'second-author', 'quantity' => 1, 'book_genre_id' => $bookGenre->id]);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::USER_ROUTE . '?filter[author]=' . 'first');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($firstBook->id, $responseData['data'][0]['id']);
    }

    public function test_admin_can_filter_books_based_on_genre() {

        $firstBookGenre = BookGenre::factory()->create(['name' => 'first_genre']);
        $secondBookGenre = BookGenre::factory()->create(['name' => 'second_genre']);
        $firstBook = Book::factory()->create(['book_genre_id' => $firstBookGenre->id, 'quantity' => 1]);
        Book::factory()->create(['book_genre_id' => $secondBookGenre->id, 'quantity' => 1]);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::USER_ROUTE . '?filter[bookGenre.name]=' . 'first');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($firstBook->id, $responseData['data'][0]['id']);
    }


    public function test_users_view_book_by_id() {

        $bookGenre = BookGenre::factory()->create();
        $firstBook = Book::factory()->create(['quantity' => 5, 'book_genre_id' => $bookGenre->id]);

        $user = $this->createUser();

        $response = $this->actingAs($user)->get(self::USER_ROUTE . '/' . $firstBook->id);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertEquals($firstBook->id, $responseData['data']['id']);
    }

    protected function createUser() {

        $userRole = Role::create(['name' => 'user']);

        $user = User::factory()->create();
        $user->attachRole($userRole->name);

        return $user;
    }
}

