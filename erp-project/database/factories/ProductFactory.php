<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(3, true)),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####??')),
            'description' => $this->faker->sentence(15),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'quantity' => $this->faker->numberBetween(10, 100),
        ];
    }
}

