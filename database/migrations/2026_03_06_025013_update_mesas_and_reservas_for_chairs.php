<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar columnas para sillas
        Schema::table('reservas', function (Blueprint $table) {
            $table->json('sillas_reservadas')->nullable()->after('mesa_id');
            $table->boolean('mesa_completa')->default(false)->after('sillas_reservadas');
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['sillas_reservadas', 'mesa_completa']);
        });
    }
};
