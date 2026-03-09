<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->dropUnique('dispositivos_tipo_red_unique');

            $table->dropColumn('red_id');
        });
    }

    public function down(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->unsignedBigInteger('red_id')->nullable()->after('tipo_dispositivo');

            $table->unique(['tipo_dispositivo', 'red_id'], 'dispositivos_tipo_red_unique');
        });
    }
};
