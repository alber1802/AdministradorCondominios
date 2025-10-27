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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietario_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('especie', 50)->comment('perro, gato, ave, etc.');
            $table->string('raza', 100)->nullable();
            $table->string('sexo', 10)->nullable()->comment('macho, hembra');
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('peso_kg', 5, 2)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('estado', 20)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
