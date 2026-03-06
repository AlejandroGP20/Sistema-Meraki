<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('horario'); // 19:00, 21:30
            $table->string('nombre');
            $table->string('artista')->nullable();
            $table->string('genero_musical')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_entrada', 8, 2)->default(40.00);
            $table->decimal('precio_entrada_cena', 8, 2)->default(70.00);
            $table->decimal('precio_vip', 8, 2)->default(50.00);
            $table->enum('estado', ['activo', 'inactivo', 'agotado'])->default('activo');
            $table->enum('tipo', ['mesas', 'standing'])->default('mesas');
            $table->integer('cupo_maximo')->default(250);
            $table->integer('cupo_disponible')->default(250);
            $table->timestamps();
            
            $table->index(['fecha', 'horario']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funciones');
    }
};
