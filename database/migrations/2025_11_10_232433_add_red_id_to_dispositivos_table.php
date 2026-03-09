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
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->foreignId('red_id')
                  ->nullable()
                  ->after('tipo_dispositivo') 
                  ->constrained('redes')
                  ->onDelete('set null');
        });
    }


    public function down(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->dropForeign(['red_id']);
            $table->dropColumn('red_id');
        });
    }
};