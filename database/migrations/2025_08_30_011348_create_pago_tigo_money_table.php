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

        Schema::create('pago_tigo_money', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained()->onDelete('cascade');
            $table->string('numero_telefono', 20);
            $table->string('referencia_transaccion')->nullable();
            $table->string('qr')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_tigo_money');
    }
};
