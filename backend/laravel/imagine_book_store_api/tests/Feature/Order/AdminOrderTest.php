<?php

namespace Tests\Feature\Order;

use App\Models\Book;
use App\Models\BookGenre;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const ROUTE = 'api/v1/admin/orders';
    const TABLE = 'orders';

    public function test_admin_can_view_all_orders() {

        $this->createOrders();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json()['data']);
    }

    public function test_admin_can_view_order_by_id() {

        $orders = $this->createOrders();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '/' . $orders->first()->id);

        $response->assertStatus(200);
        $this->assertEquals($orders->first()->id, $response->json()['data']['id']);
    }

    public function test_admin_can_filter_orders_by_date() {

        $orders = $this->createOrders();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '?filter[date]='. $orders->first()->date);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
        $this->assertEquals($orders->first()->id, $response->json()['data'][0]['id']);
    }

    public function test_admin_can_filter_orders_by_user_name() {

        $orders = $this->createOrders();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '?filter[user.name]='. $orders->first()->user->name);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
        $this->assertEquals($orders->first()->id, $response->json()['data'][0]['id']);
    }

    public function test_admin_can_include_user_with_orders() {

        $this->createOrders();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '?include[]=user');

        $response->assertStatus(200);

        $this->assertCount(2, $response->json()['data']);

        $response->assertJsonStructure([
            "data" => [
                0 => ["user"],
                1 => ["user"]
            ]
        ]);
    }

    public function test_admin_can_include_books_with_orders() {

        $this->createOrders();

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(self::ROUTE . '?include[]=books');

        $response->assertStatus(200);

        $this->assertCount(2, $response->json()['data']);

        $response->assertJsonStructure([
            "data" => [
                0 => ["books"],
                1 => ["books"]
            ]
        ]);
    }


    protected function createOrders() {

        $firstUser = $this->createUser();
        $secondUser = $this->createUser();

        $bookGenre = $this->createBookGenre();

        $firstBook = $this->createBook($bookGenre->id, 10);
        $secondBook = $this->createBook($bookGenre->id, 10);


        return collect([$this->createOrder($firstUser->id, [$firstBook, $secondBook]),$this->createOrder($secondUser->id, [$firstBook, $secondBook])]);
    }


    /* Data Seeding Helpers */

    protected function createAdmin()
    {

        $adminRole = Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->attachRole($adminRole->name);

        return $admin;
    }

    protected function createUser() {

        $userRole = Role::create(['name' => 'user']);

        $user = User::factory()->create();
        $user->attachRole($userRole->name);

        return $user;
    }

    protected function createBookGenre() {

        return BookGenre::factory()->create();
    }

    protected function createBook($bookGenreId, $quantity) {

        return Book::factory()->create(['book_genre_id' => $bookGenreId, 'quantity' => $quantity]);
    }

    protected function createOrder($userId, $books) {

       Order::query()->make()->forceFill(['user_id' => $userId, 'date' => $this->faker->dateTime(), 'total_price' => 0])->saveQuietly();

       $order = Order::where('user_id', $userId)->first();

        foreach ($books as $book)
        {
            DB::table('book_order')->insert([
                'order_id' => $order->id,
                'book_id' => $book->id,
                'unit_price' => $book->price,
                'quantity' => 1,
            ]);
        }

        return $order;
    }
}
