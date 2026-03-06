<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesasRealesSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar foreign keys temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar mesas existentes
        DB::table('mesas')->truncate();
        
        // Reactivar foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $mesas = [
            // FILA 1 - Frente al escenario (Mesas 1-6)
            ['numero' => 1, 'zona' => 'escenario', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 100, 'coord_y' => 180],
            ['numero' => 2, 'zona' => 'escenario', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 200, 'coord_y' => 180],
            ['numero' => 3, 'zona' => 'escenario', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 300, 'coord_y' => 180],
            ['numero' => 4, 'zona' => 'escenario', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 400, 'coord_y' => 180],
            ['numero' => 5, 'zona' => 'escenario', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 500, 'coord_y' => 180],
            ['numero' => 6, 'zona' => 'escenario', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 600, 'coord_y' => 180],
            
            // FILA 2 - Centro (Mesas 7-14)
            ['numero' => 7, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 100, 'coord_y' => 300],
            ['numero' => 8, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 200, 'coord_y' => 300],
            ['numero' => 9, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 300, 'coord_y' => 300],
            ['numero' => 10, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 400, 'coord_y' => 300],
            ['numero' => 11, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 500, 'coord_y' => 300],
            ['numero' => 12, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 600, 'coord_y' => 300],
            ['numero' => 13, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 700, 'coord_y' => 300],
            ['numero' => 14, 'zona' => 'centro', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 800, 'coord_y' => 300],
            
            // FILA 3 - Fondo (Mesas 15-23)
            ['numero' => 15, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 100, 'coord_y' => 420],
            ['numero' => 16, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 200, 'coord_y' => 420],
            ['numero' => 17, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 300, 'coord_y' => 420],
            ['numero' => 18, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 400, 'coord_y' => 420],
            ['numero' => 19, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 500, 'coord_y' => 420],
            ['numero' => 20, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 600, 'coord_y' => 420],
            ['numero' => 21, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 700, 'coord_y' => 420],
            ['numero' => 22, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 800, 'coord_y' => 420],
            ['numero' => 23, 'zona' => 'fondo', 'capacidad' => 3, 'tipo' => 'mesa', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 900, 'coord_y' => 420],
            
            // BARRA - 9 Taburetes (Mesas 24-32)
            ['numero' => 24, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 750, 'coord_y' => 80],
            ['numero' => 25, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 780, 'coord_y' => 80],
            ['numero' => 26, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 810, 'coord_y' => 80],
            ['numero' => 27, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 840, 'coord_y' => 80],
            ['numero' => 28, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 870, 'coord_y' => 80],
            ['numero' => 29, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 900, 'coord_y' => 80],
            ['numero' => 30, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 750, 'coord_y' => 120],
            ['numero' => 31, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 780, 'coord_y' => 120],
            ['numero' => 32, 'zona' => 'barra', 'capacidad' => 1, 'tipo' => 'taburete', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 810, 'coord_y' => 120],
        ];

        foreach ($mesas as $mesa) {
            Mesa::create($mesa);
        }
    }
}
