<?php

/**
 * Script para marcar la primera imagen de cada función como principal
 * si la función no tiene ninguna imagen principal
 * 
 * Ejecutar: php fix-principal-images.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Funcion;

echo "🔧 Corrigiendo imágenes principales...\n\n";

$funciones = Funcion::with('imagenes')->get();
$corregidas = 0;

foreach ($funciones as $funcion) {
    // Verificar si tiene imágenes
    if ($funcion->imagenes->isEmpty()) {
        echo "⚠️  Función #{$funcion->id} '{$funcion->nombre}' no tiene imágenes\n";
        continue;
    }

    // Verificar si ya tiene una imagen principal
    $tienePrincipal = $funcion->imagenes->where('es_principal', true)->isNotEmpty();
    
    if ($tienePrincipal) {
        echo "✅ Función #{$funcion->id} '{$funcion->nombre}' ya tiene imagen principal\n";
        continue;
    }

    // Marcar la primera imagen como principal
    $primeraImagen = $funcion->imagenes->first();
    $primeraImagen->update(['es_principal' => true]);
    
    echo "🎯 Función #{$funcion->id} '{$funcion->nombre}' - Imagen #{$primeraImagen->id} marcada como principal\n";
    $corregidas++;
}

echo "\n✨ Proceso completado!\n";
echo "📊 Total de funciones corregidas: {$corregidas}\n";
