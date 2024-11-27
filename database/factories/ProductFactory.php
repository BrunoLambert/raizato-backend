<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
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
        $categories = array_column(Category::all("id")->toArray(), "id");
        $suppliers = array_column(Supplier::all("id")->toArray(), "id");

        return [
            "name" => fake()->word(),
            "code" => fake()->numberBetween(1, 10000),
            "description" => fake()->text(),
            "cost_price" => fake()->randomFloat(2, 1, 500),
            "selling_price" => fake()->randomFloat(2, 501, 1000),
            "minimum_stock" => fake()->numberBetween(1, 100),
            "expiration_date" => fake()->dateTimeInInterval("-2 weeks", "+1 month"),
            "category_id" => fake()->randomElement($categories),
            "supplier_id" => fake()->randomElement($suppliers)
        ];
    }
}
