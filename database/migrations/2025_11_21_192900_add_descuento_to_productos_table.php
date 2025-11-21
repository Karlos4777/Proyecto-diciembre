<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescuentoToProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('productos')) {
            return;
        }

        Schema::table('productos', function (Blueprint $table) {
            if (!Schema::hasColumn('productos', 'descuento')) {
                // Usamos unsignedTinyInteger para 0-255, suficiente para porcentajes
                $table->unsignedTinyInteger('descuento')->default(0)->after('precio');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('productos')) {
            return;
        }

        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'descuento')) {
                $table->dropColumn('descuento');
            }
        });
    }
}
