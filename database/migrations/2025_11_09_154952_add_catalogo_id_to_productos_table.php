    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('productos', function (Blueprint $table) {
                $table->foreignId('catalogo_id')      
                    ->nullable()
                    ->constrained('catalogos')     
                    ->onDelete('set null');
            });
        }

        public function down(): void
        {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropForeign(['catalogo_id']); 
                $table->dropColumn('catalogo_id');   
            });
        }
    };
