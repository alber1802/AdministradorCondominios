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

        Schema::create('reservas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('area_id')->constrained('area_comuns');
            $table->foreignId('user_id')->constrained();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->string('estado', 20);
            $table->string('qr_code')->nullable();
            $table->foreignId('area_comun_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
