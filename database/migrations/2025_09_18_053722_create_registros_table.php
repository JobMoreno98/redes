<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->string('ip',15)->unique();
            $table->char('mac',17)->unique();
            $table->string('numero_serie',100)->unique();
            $table->string('descripcion',255)->nullable();
            $table->string('responsable',100)->nullable();
            $table->foreignId('dependencia_id')->constrained()->onDelete('cascade');
            $table->foreignId('segmento_id')->constrained()->onDelete('cascade');
            $table->foreignId('tipo_dispositivo_id')->constrained('dispositivos')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
