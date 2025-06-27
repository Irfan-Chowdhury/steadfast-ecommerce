<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->unique()->word,
            'purchase_price' => fake()->randomFloat(2, 10, 1000),
            'sell_price' => fake()->randomFloat(2, 15, 1500),
            'opening_stock' => fake()->numberBetween(0, 100),
            'current_stock' => function (array $attributes) {
                return $attributes['opening_stock'];
            },
        ];
    }
}
