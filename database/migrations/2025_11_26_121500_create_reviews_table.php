<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->tinyInteger('rating'); // 1-5
            $table->text('comentario')->nullable();
            $table->boolean('aprobado')->default(true); // moderación futura
            $table->timestamps();
            $table->unique(['user_id','producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
