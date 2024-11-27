<?php

use App\Enums\StockLogTypeEnum;
use App\Models\Stock;
use App\Models\User;
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
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->smallInteger("quantity")->unsigned();
            $table->enum("type", array_column(StockLogTypeEnum::cases(), 'value'));

            $table->foreignIdFor(Stock::class);
            $table->foreignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
