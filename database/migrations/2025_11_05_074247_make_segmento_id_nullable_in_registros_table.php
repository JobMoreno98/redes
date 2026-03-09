<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registros', function (Blueprint $table) {
            $table->dropForeign(['segmento_id']);
        });

        Schema::table('registros', function (Blueprint $table) {
            $table->unsignedBigInteger('segmento_id')->nullable()->change();
        });

        Schema::table('registros', function (Blueprint $table) {
            $table->foreign('segmento_id')
                  ->references('id')->on('segmentos')
                  ->onDelete('SET NULL');
        });
    }

    public function down(): void
    {
        Schema::table('registros', function (Blueprint $table) {
            $table->dropForeign(['segmento_id']);
            $table->unsignedBigInteger('segmento_id')->nullable(false)->change();
            $table->foreign('segmento_id')
                  ->references('id')->on('segmentos')
                  ->onDelete('CASCADE');
        });
    }
};
