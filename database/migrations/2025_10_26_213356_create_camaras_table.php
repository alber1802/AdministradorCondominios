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
        Schema::create('camaras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Ej: Cámara Lobby Principal');
            $table->string('marca', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->string('numero_serie', 100)->unique();
            $table->string('tipo', 30)->nullable()->comment('IP, Domo, Bala, PTZ, etc.');
            $table->string('resolucion', 20)->nullable()->comment('1080p, 4K, etc.');
            $table->string('ubicacion', 255)->comment('Ej: Pasillo Piso 2, Ala Norte');
            $table->string('direccion_ip', 45)->unique()->nullable()->comment('Puede ser IPv4 o IPv6');
            $table->string('estado', 20)->default('activa')->comment('activa, inactiva, mantenimiento');
            $table->date('fecha_instalacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camaras');
    }
};
