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
        Schema::table('pedido_referencias', function (Blueprint $table) {
            $table->foreignId('detalle_id')->nullable()->after('pedido_id')->constrained('pedido_detalles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_referencias', function (Blueprint $table) {
            $table->dropConstrainedForeignId('detalle_id');
        });
    }
};
