<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la columna 'ventas' a la tabla productos.
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Agrega una columna 'ventas' para contar las ventas
            $table->unsignedInteger('ventas')->default(0)->after('cantidad');
        });
    }

    /**
     * Revierte los cambios si se hace rollback.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('ventas');
        });
    }
};
