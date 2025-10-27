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
        Schema::disableForeignKeyConstraints();

        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 100);
            $table->string('piso', 50)->nullable();
            $table->string('bloque', 50)->default('A');
            $table->boolean('estado')->default(false);
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
