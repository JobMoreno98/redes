<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('registros', function (Blueprint $table) {
        $table->foreignId('red_id')->nullable()->constrained('redes')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('registros', function (Blueprint $table) {
        $table->dropForeign(['red_id']);
        $table->dropColumn('red_id');
    });
}

};
