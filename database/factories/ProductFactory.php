<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence($nbWords = 2),
            'description' => $this->faker->sentence($nbWords = 5),
            'price' => $this->faker->numberBetween($min = 15, $max = 100),
        ];
    }
}
