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

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('factura_id')->constrained()->onDelete('cascade');
            $table->string('tipo_pago', 30)->nullable();
            $table->decimal('monto_pagado', 10, 2);
            $table->date('fecha_pago');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
