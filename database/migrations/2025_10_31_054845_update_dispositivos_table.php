<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            if (Schema::hasColumn('dispositivos', 'prefijo')) {
                $table->dropColumn('prefijo');
            }

            if (!Schema::hasColumn('dispositivos', 'red_id')) {
                $table->foreignId('red_id')
                    ->nullable()
                    ->after('tipo_dispositivo')
                    ->constrained('redes')
                    ->onDelete('cascade');
            }

            $table->unique(['tipo_dispositivo', 'red_id'], 'dispositivos_tipo_red_unique');
        });
    }

    public function down(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->dropForeign(['red_id']);
            $table->dropUnique('dispositivos_tipo_red_unique');

            if (Schema::hasColumn('dispositivos', 'red_id')) {
                $table->dropColumn('red_id');
            }

            if (!Schema::hasColumn('dispositivos', 'prefijo')) {
                $table->string('prefijo', 8)->nullable();
            }
        });
    }
};
