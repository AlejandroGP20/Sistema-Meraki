<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reserva')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('funcion_id')->constrained('funciones')->onDelete('cascade');
            $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');
            $table->integer('num_personas');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->decimal('monto_total', 8, 2);
            $table->boolean('incluye_cena')->default(false);
            $table->boolean('es_vip')->default(false);
            $table->text('notas')->nullable();
            $table->timestamp('check_in_at')->nullable();
            $table->timestamps();
            
            $table->index(['funcion_id', 'mesa_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
