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

        Schema::create('facturas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('departamento_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('tipo', 50);
            $table->decimal('monto', 10, 2);
            $table->string('estado', 20)->default('pendiente');
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('comprobante_pdf')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
