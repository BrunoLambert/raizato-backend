<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\Supplier;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            "fullname" => "Bruno Lambert",
            "email" => "brunofsclambert@gmail.com",
            "cellphone" => "5511948560966",
            "password" => "1324",
            "role" => "admin"
        ]);
        User::factory()->count(50)->create();
        Category::factory()->count(100)->create();
        Supplier::factory()->count(100)->create();
        Product::factory()->count(200)->create();
        Stock::factory()->count(200)->create();

        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            StockLog::factory()->create([
                "stock_id" => $stock->id,
                "quantity" => $stock->quantity
            ]);
        }
    }
}
