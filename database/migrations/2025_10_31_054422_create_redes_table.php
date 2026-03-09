<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redes', function (Blueprint $table) {
            $table->id();
            $table->string('direccion_base', 20)->unique();
            $table->boolean('usa_segmentos')->default(false);
            $table->string('descripcion', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redes');
    }
};
