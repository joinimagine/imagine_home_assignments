<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'author' => $this->faker->name,
            'price' => $this->faker->numberBetween(10, 200),
            'quantity' => $this->faker->numberBetween(0, 5),
            'book_genre_id' => rand(1, 10)
        ];
    }
}
