<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
    $table->dropColumn('email');
    $table->unsignedInteger('codigo')->unique()->after('nombre');
});

    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('codigo');

            $table->string('email', 100)->unique();
        });
    }
};
