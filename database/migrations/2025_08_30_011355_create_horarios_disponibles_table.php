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

        Schema::create('horarios_disponibles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_comun_id')->constrained('area_comuns')->onDelete('cascade');
            $table->integer('dia_semana');
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_disponibles');
    }
};
