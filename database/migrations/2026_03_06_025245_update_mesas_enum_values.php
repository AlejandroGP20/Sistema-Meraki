<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cambiar el enum de zona para incluir 'centro' y 'fondo'
        DB::statement("ALTER TABLE mesas MODIFY COLUMN zona ENUM('escenario', 'barra', 'plaza', 'centro', 'fondo') NOT NULL");
        
        // Cambiar el enum de tipo para incluir 'mesa' y 'taburete'
        DB::statement("ALTER TABLE mesas MODIFY COLUMN tipo ENUM('estandar', 'vip', 'barra', 'mesa', 'taburete') DEFAULT 'estandar'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE mesas MODIFY COLUMN zona ENUM('escenario', 'barra', 'plaza') NOT NULL");
        DB::statement("ALTER TABLE mesas MODIFY COLUMN tipo ENUM('estandar', 'vip', 'barra') DEFAULT 'estandar'");
    }
};
