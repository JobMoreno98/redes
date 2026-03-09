<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('segmentos', function (Blueprint $table) {
            if (!Schema::hasColumn('segmentos', 'red_id')) {
                $table->foreignId('red_id')
                    ->nullable()
                    ->after('segmento')
                    ->constrained('redes')
                    ->onDelete('cascade');
            }

            $table->unique(['segmento', 'red_id'], 'segmentos_segmento_red_unique');
        });
    }

    public function down(): void
    {
        Schema::table('segmentos', function (Blueprint $table) {
            $table->dropUnique('segmentos_segmento_red_unique');

            if (Schema::hasColumn('segmentos', 'red_id')) {
                $table->dropForeign(['red_id']);
                $table->dropColumn('red_id');
            }
        });
    }
};
