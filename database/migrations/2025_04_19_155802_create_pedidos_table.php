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
            $table->date('fecha')->default(now()); // Fecha del pedido
            $table->decimal('total', 10, 2); // Total del pedido
            $table->string('estado', 20)->default('pendiente'); // Estado: pendiente, enviado, cancelado, etc.
            $table->text('detalles')->nullable(); // Detalles o descripción general del pedido
            $table->timestamps();
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
