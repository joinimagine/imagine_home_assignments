<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItems>
 */
class OrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $book_id  = 1;
        $stock_quantity = Book::find($book_id)->stock_quantity;

        return [
            'user_id' => 1,
            'book_id' => $book_id++,
            'book_price' => $this->faker->numberBetween(0, 10000) / 100,
            'quantity' => $this->faker->numberBetween(0, $stock_quantity)
        ];
    }
}
