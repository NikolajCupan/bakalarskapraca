<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WarehouseProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Return instance of User
        return [
            'product' => $this->faker->word,
            'quantity' => rand(0, 100000)
        ];
    }
}
