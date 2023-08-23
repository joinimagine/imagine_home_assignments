<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, 10),
            'date' => $this->faker->dateTime,
            'total_price' => $this->faker->numberBetween(100, 1000)
        ];
    }
}
