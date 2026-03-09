<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
        
            $table->string('codigo', 9)->change();

            
            if (Schema::hasColumn('usuarios', 'area')) {
                $table->dropColumn('area');
            }
            if (Schema::hasColumn('usuarios', 'sede')) {
                $table->dropColumn('sede');
            }

            
            if (!Schema::hasColumn('usuarios', 'dependencia_id')) {
                $table->foreignId('dependencia_id')->nullable()->constrained('dependencias')->after('codigo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('area', 100)->nullable();
            $table->string('sede', 100)->nullable();

            if (Schema::hasColumn('usuarios', 'dependencia_id')) {
                $table->dropForeign(['dependencia_id']);
                $table->dropColumn('dependencia_id');
            }

            $table->unsignedInteger('codigo')->change();
        });
    }
};
