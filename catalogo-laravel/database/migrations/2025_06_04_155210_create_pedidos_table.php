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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('direccion_id')->constrained('direcciones');
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('pendiente'); // pendiente, completado, cancelado
            $table->dateTime('fecha_pedido')->useCurrent();
            $table->dateTime('created_at', 6)->nullable(); // Cambia aquí
            $table->dateTime('updated_at', 6)->nullable(); // Cambia aquí
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
