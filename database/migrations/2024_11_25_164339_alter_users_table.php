<?php

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
        // nome completo, celular para contato, e-mail e papel
        Schema::table("users", function (Blueprint $table) {
            $table->renameColumn("name", "fullname");
            $table->string("cellphone", length: 14); // +5511948560966
            $table->enum("role", ["admin", "manager", "common"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
