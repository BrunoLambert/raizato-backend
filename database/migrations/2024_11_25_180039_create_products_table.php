<?php

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string("name");
            $table->string("code", length: 10);
            $table->text("description");
            $table->decimal("cost_price", 10, 2);
            $table->decimal("selling_price", 10, 2);
            $table->smallInteger("minimum_stock")->unsigned();
            $table->date("expiration_date");

            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Supplier::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
