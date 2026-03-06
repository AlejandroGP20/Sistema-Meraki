<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->unique();
            $table->enum('zona', ['escenario', 'barra', 'plaza']); // 1-8, 9-16, 17-25
            $table->integer('capacidad');
            $table->enum('tipo', ['estandar', 'vip', 'barra'])->default('estandar');
            $table->boolean('es_vip')->default(false);
            $table->enum('forma', ['circulo', 'cuadrado', 'hexagono'])->default('circulo');
            $table->decimal('coord_x', 8, 2);
            $table->decimal('coord_y', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
