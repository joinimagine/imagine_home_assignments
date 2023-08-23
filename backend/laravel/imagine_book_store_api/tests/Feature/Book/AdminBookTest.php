<?php


namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookGenre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookTest extends TestCase
{
    use RefreshDatabase;

    const ADMIN_ROUTE = 'api/v1/admin/books';
    const TABLE = 'books';


    public function test_admin_can_view_all_book()
    {

        $bookGenre = BookGenre::factory()->create();
        Book::factory()->create(['quantity' => 5, 'book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['quantity' => 0, 'book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ADMIN_ROUTE);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json()['data']);
    }

    public function test_admin_can_access_books_with_accurate_pagination()
    {

        $totalBooks = 10;
        $perPage = 5;

        $bookGenre = BookGenre::factory()->create();
        Book::factory($totalBooks)->create(['book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ADMIN_ROUTE . '?perPage=' . $perPage);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount($perPage, $responseData['data']);
        $this->assertEquals(1, $responseData['meta']['current_page']);
        $this->assertEquals(($totalBooks / $perPage), $responseData['meta']['last_page']);
        $this->assertEquals($totalBooks, $responseData['meta']['total']);
        $this->assertEquals($perPage, $responseData['meta']['per_page']);
    }

    public function test_admin_can_filter_books_based_on_title() {

        $bookGenre = BookGenre::factory()->create();
        $firstBook = Book::factory()->create(['title' => 'first-book','book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['title' => 'second-book', 'book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ADMIN_ROUTE . '?filter[title]=' . 'first');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($firstBook->id, $responseData['data'][0]['id']);
    }

    public function test_admin_can_filter_books_based_on_author() {

        $bookGenre = BookGenre::factory()->create();
        $firstBook = Book::factory()->create(['author' => 'first-author', 'book_genre_id' => $bookGenre->id]);
        Book::factory()->create(['author' => 'second-author', 'book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ADMIN_ROUTE . '?filter[author]=' . 'first');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($firstBook->id, $responseData['data'][0]['id']);
    }

    public function test_admin_can_filter_books_based_on_genre() {

        $firstBookGenre = BookGenre::factory()->create(['name' => 'first_genre']);
        $secondBookGenre = BookGenre::factory()->create(['name' => 'second_genre']);
        $firstBook = Book::factory()->create(['book_genre_id' => $firstBookGenre->id]);
        Book::factory()->create(['book_genre_id' => $secondBookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ADMIN_ROUTE . '?filter[bookGenre.name]=' . 'first');
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(1, $responseData['data']);
        $this->assertEquals($firstBook->id, $responseData['data'][0]['id']);
    }

    public function test_admin_can_view_book_by_id() {

        $bookGenre = BookGenre::factory()->create();
        $firstBook = Book::factory()->create(['quantity' => 5, 'book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ADMIN_ROUTE . '/' . $firstBook->id);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertEquals($firstBook->id, $responseData['data']['id']);
    }

    public function test_admin_can_store_new_book() {

        $bookGenre = BookGenre::factory()->create();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(self::ADMIN_ROUTE, [
            'title' => 'test',
            'author' => 'test',
            'price' => 200,
            'quantity' => 2,
            'book_genre_id' => $bookGenre->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('books', 1);
    }

    public function test_admin_cannot_store_book_with_zero_price() {

        $bookGenre = BookGenre::factory()->create();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(self::ADMIN_ROUTE, [
            'title' => 'test',
            'author' => 'test',
            'price' => 0,
            'quantity' => 2,
            'book_genre_id' => $bookGenre->id
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('books', 0);
    }

    public function test_admin_can_update_book() {

        $bookGenre = BookGenre::factory()->create();
        $book = Book::factory()->create(['book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->put(self::ADMIN_ROUTE . '/' . $book->id, [
            'title' => 'updated_title',
        ]);

        $book->refresh();

        $response->assertStatus(200);
        $this->assertEquals('updated_title', $book->title);
    }

    public function test_admin_can_delete_book() {

        $bookGenre = BookGenre::factory()->create();
        $book = Book::factory()->create(['book_genre_id' => $bookGenre->id]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(self::ADMIN_ROUTE . '/' . $book->id);

        $response->assertStatus(200);
        $this->assertDatabaseCount('books', 0);
    }


    protected function createAdmin()
    {

        $adminRole = Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->attachRole($adminRole->name);

        return $admin;
    }

}
