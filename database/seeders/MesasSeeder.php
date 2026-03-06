<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesasSeeder extends Seeder
{
    public function run(): void
    {
        $mesas = [
            // Zona Escenario (Mesas 1-8) - Círculos
            ['numero' => 1, 'zona' => 'escenario', 'capacidad' => 2, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 100, 'coord_y' => 100],
            ['numero' => 2, 'zona' => 'escenario', 'capacidad' => 4, 'tipo' => 'vip', 'es_vip' => true, 'forma' => 'circulo', 'coord_x' => 200, 'coord_y' => 100],
            ['numero' => 3, 'zona' => 'escenario', 'capacidad' => 2, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 300, 'coord_y' => 100],
            ['numero' => 4, 'zona' => 'escenario', 'capacidad' => 4, 'tipo' => 'vip', 'es_vip' => true, 'forma' => 'circulo', 'coord_x' => 400, 'coord_y' => 100],
            ['numero' => 5, 'zona' => 'escenario', 'capacidad' => 2, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 100, 'coord_y' => 200],
            ['numero' => 6, 'zona' => 'escenario', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 200, 'coord_y' => 200],
            ['numero' => 7, 'zona' => 'escenario', 'capacidad' => 2, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'circulo', 'coord_x' => 300, 'coord_y' => 200],
            ['numero' => 8, 'zona' => 'escenario', 'capacidad' => 4, 'tipo' => 'vip', 'es_vip' => true, 'forma' => 'circulo', 'coord_x' => 400, 'coord_y' => 200],
            
            // Zona Barra (Mesas 9-16) - Cuadrados
            ['numero' => 9, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 100, 'coord_y' => 350],
            ['numero' => 10, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 200, 'coord_y' => 350],
            ['numero' => 11, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 300, 'coord_y' => 350],
            ['numero' => 12, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 400, 'coord_y' => 350],
            ['numero' => 13, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 100, 'coord_y' => 450],
            ['numero' => 14, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 200, 'coord_y' => 450],
            ['numero' => 15, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 300, 'coord_y' => 450],
            ['numero' => 16, 'zona' => 'barra', 'capacidad' => 2, 'tipo' => 'barra', 'es_vip' => false, 'forma' => 'cuadrado', 'coord_x' => 400, 'coord_y' => 450],
            
            // Zona Plaza/Patio (Mesas 17-25) - Hexágonos
            ['numero' => 17, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 100, 'coord_y' => 600],
            ['numero' => 18, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 250, 'coord_y' => 600],
            ['numero' => 19, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 400, 'coord_y' => 600],
            ['numero' => 20, 'zona' => 'plaza', 'capacidad' => 6, 'tipo' => 'vip', 'es_vip' => true, 'forma' => 'hexagono', 'coord_x' => 175, 'coord_y' => 700],
            ['numero' => 21, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 325, 'coord_y' => 700],
            ['numero' => 22, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 100, 'coord_y' => 800],
            ['numero' => 23, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 250, 'coord_y' => 800],
            ['numero' => 24, 'zona' => 'plaza', 'capacidad' => 4, 'tipo' => 'estandar', 'es_vip' => false, 'forma' => 'hexagono', 'coord_x' => 400, 'coord_y' => 800],
            ['numero' => 25, 'zona' => 'plaza', 'capacidad' => 6, 'tipo' => 'vip', 'es_vip' => true, 'forma' => 'hexagono', 'coord_x' => 250, 'coord_y' => 900],
        ];

        foreach ($mesas as $mesa) {
            Mesa::create($mesa);
        }
    }
}
