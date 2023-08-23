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

class UserOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const ROUTE = 'api/v1/orders';
    const TABLE = 'orders';

    public function test_user_can_only_view_his_orders() {

        $orders = $this->createOrders();

        $user = $orders->first()->user;

        $response = $this->actingAs($user)->get(self::ROUTE);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
        $this->assertEquals($orders->first()->id, $response->json()['data'][0]['id']);
    }

    public function test_user_can_view_order_by_id() {

        $orders = $this->createOrders();

        $order = $orders->first();

        $user = $order->user;

        $response = $this->actingAs($user)->get(self::ROUTE . '/' . $order->id);

        $response->assertStatus(200);
        $this->assertEquals($order->id, $response->json()['data']['id']);
    }


    public function test_user_can_create_order_from_his_valid_cart() {

        $user = $this->createUser();

        $bookGenre = $this->createBookGenre();

        $firstBook = $this->createBook($bookGenre->id, 10);
        $secondBook = $this->createBook($bookGenre->id, 20);

        $user->cart()->attach($firstBook->id, ['quantity' => 5]);
        $user->cart()->attach($secondBook->id, ['quantity' => 5]);

        $response = $this->actingAs($user)->post(self::ROUTE);

        $response->assertStatus(201);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('book_order', 2);
    }

    public function test_user_cannot_create_order_from_his_invalid_cart() {

        $user = $this->createUser();

        $bookGenre = $this->createBookGenre();

        $firstBook = $this->createBook($bookGenre->id, 10);
        $secondBook = $this->createBook($bookGenre->id, 20);

        /* Add books to user's cart. */
        $user->cart()->attach($firstBook->id, ['quantity' => 5]);
        $user->cart()->attach($secondBook->id, ['quantity' => 5]);

        /*  Reduce Books quantity before ordering the cart.  */
        $firstBook->update(['quantity' => 4]);
        $secondBook->update(['quantity' => 4]);

        $response = $this->actingAs($user)->post(self::ROUTE);

        $response->assertStatus(400);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('book_order', 0);
    }

    public function test_book_quantity_is_updated_when_storing_order() {

        $user = $this->createUser();

        $bookGenre = $this->createBookGenre();

        $firstBook = $this->createBook($bookGenre->id, 10);
        $secondBook = $this->createBook($bookGenre->id, 20);

        /* Add books to user's cart. */
        $user->cart()->attach($firstBook->id, ['quantity' => 5]);
        $user->cart()->attach($secondBook->id, ['quantity' => 5]);

        $response = $this->actingAs($user)->post(self::ROUTE);

        $response->assertStatus(201);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('book_order', 2);

        $firstBook->refresh();
        $secondBook->refresh();

        $this->assertEquals(5, $firstBook->quantity);
        $this->assertEquals(15, $secondBook->quantity);
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
