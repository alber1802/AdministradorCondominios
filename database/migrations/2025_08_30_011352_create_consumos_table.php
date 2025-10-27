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

        Schema::create('consumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained();
            $table->string('tipo', 30);
            $table->decimal('lectura', 10, 2);
            $table->string('unidad', 10);
            $table->date('fecha');
            $table->decimal('costo_unitario', 8, 2);
            $table->boolean('alerta');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumos');
    }
};
