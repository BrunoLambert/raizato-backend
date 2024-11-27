<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = array_column(Product::all("id")->toArray(), "id");

        return [
            "quantity" => fake()->numberBetween(1, 100),
            "product_id" => fake()->randomElement($products)
        ];
    }
}
