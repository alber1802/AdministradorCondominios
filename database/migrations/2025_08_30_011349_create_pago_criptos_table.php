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

        Schema::create('pago_criptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained()->onDelete('cascade');
            $table->string('wallet_origen', 120);
            $table->string('wallet_destino', 120);
            $table->string('moneda', 10);
            $table->string('hash_transaccion', 120);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_criptos');
    }
};
