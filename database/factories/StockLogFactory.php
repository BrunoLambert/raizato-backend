<?php

namespace Database\Factories;

use App\Enums\StockLogTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StockLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = array_column(User::all("id")->toArray(), 'id');

        return [
            "quantity" => 0,
            "type" => StockLogTypeEnum::Creation,
            "user_id" => fake()->randomElement($users),
            "stock_id" => 0
        ];
    }
}
