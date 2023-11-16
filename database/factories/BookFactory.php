<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $genres = ['Horror', 'Romance', 'Mystery', 'Drama', 'Music', 'Education', 'Adventure'];

        $genre = $genres[$this->faker->numberBetween(0, floor((count($genres) - 1) / 2))];

        return [
            'title' => $this->faker->title(),
            'author' => $this->faker->name(),
            'genre' => $genre . ', ' . $genres[$this->faker->numberBetween((ceil((count($genres) - 1) / 2)), count($genres) - 1)],
            'price' => $this->faker->numberBetween(0, 10000) / 100,
            'stock_quantity' => $this->faker->numberBetween(0, 10000)
        ];
    }
}
