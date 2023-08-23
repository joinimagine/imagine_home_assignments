<?php

namespace Tests\Feature;

use App\Models\BookGenre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookGenreTest extends TestCase
{

    use RefreshDatabase;

    const ROUTE = 'api/v1/admin/book-genres';
    const TABLE = 'book_genres';

    public function test_admin_can_access_book_genres_with_accurate_pagination() {

        $totalBookGenres = 10;
        $perPage = 5;

        BookGenre::factory($totalBookGenres)->create();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '?perPage=' . $perPage);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount($perPage, $responseData['data']);
        $this->assertEquals(1, $responseData['meta']['current_page']);
        $this->assertEquals(($totalBookGenres / $perPage), $responseData['meta']['last_page']);
        $this->assertEquals($totalBookGenres, $responseData['meta']['total']);
        $this->assertEquals($perPage, $responseData['meta']['per_page']);
    }


    public function test_admin_can_filter_book_genres_based_on_name() {

        $firstBookGenre = BookGenre::factory()->create(['name' => 'first_book_genre']);
        BookGenre::factory()->create(['name' => 'second_book_genre']);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '?filter[name]=first');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
        $this->assertEquals($firstBookGenre->id, $response->json()['data'][0]['id']);
        $this->assertEquals($firstBookGenre->name, $response->json()['data'][0]['name']);
    }

    public function test_admin_can_view_book_genre_by_id() {

        $bookGenre = BookGenre::factory()->create(['name' => 'first_book_genre']);
        BookGenre::factory()->create(['name' => 'second_book_genre']);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '/' . $bookGenre->id);

        $response->assertStatus(200);
        $this->assertEquals($bookGenre->id, $response->json()['data']['id']);
    }

    public function test_admin_can_store_book_genre() {

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(self::ROUTE, [
            'name' => 'test_name'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseCount(self::TABLE, 1);
    }

    public function test_admin_cannot_store_two_book_genres_with_the_same_name() {

        $name = 'test_book_genre';
        BookGenre::factory()->create(['name' => $name]);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(self::ROUTE, [
            'name' => $name
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount(self::TABLE, 1);
    }

    public function test_admin_can_update_book_genre() {

        $bookGenre = BookGenre::factory()->create(['name' => 'test_name']);

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->put(self::ROUTE . '/' . $bookGenre->id, [
            'name' => 'new_name'
        ]);

        $response->assertStatus(200);

        $bookGenre->refresh();

        $this->assertEquals('new_name', $bookGenre->name);
    }

    public function test_admin_can_delete_book_genre() {

        $bookGenre = BookGenre::factory()->create();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(self::ROUTE . '/' . $bookGenre->id);

        $response->assertStatus(200);

        $this->assertDatabaseCount(self::TABLE, 0);
    }


    protected function createAdmin() {

        $adminRole = Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->attachRole($adminRole->name);

        return $admin;
    }

}
