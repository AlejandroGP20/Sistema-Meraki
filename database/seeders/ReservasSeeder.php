<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Funcion;
use App\Models\Mesa;
use App\Models\Reserva;
use Illuminate\Support\Str;

class ReservasSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios, funciones y mesas
        $clientes = User::role('cliente')->get();
        $funciones = Funcion::all();
        $mesas = Mesa::all();

        if ($clientes->isEmpty() || $funciones->isEmpty() || $mesas->isEmpty()) {
            $this->command->warn('No hay suficientes datos. Asegúrate de ejecutar UsersSeeder, FuncionesSeeder y MesasSeeder primero.');
            return;
        }

        $estados = ['pendiente', 'confirmada', 'cancelada'];
        
        // Crear 20 reservas de ejemplo
        for ($i = 0; $i < 20; $i++) {
            $cliente = $clientes->random();
            $funcion = $funciones->random();
            $mesa = $mesas->random();
            $numPersonas = rand(1, min(3, $mesa->capacidad));
            $esVip = rand(0, 1) == 1;
            $incluyeCena = rand(0, 1) == 1;
            
            // Calcular precio
            $precioPorPersona = $esVip ? 50 : 40;
            if ($incluyeCena) {
                $precioPorPersona += 30;
            }
            $montoTotal = $precioPorPersona * $numPersonas;
            
            // Sillas reservadas
            $sillasReservadas = [];
            if ($mesa->capacidad == 1) {
                $sillasReservadas = [1];
            } else {
                for ($j = 1; $j <= $numPersonas; $j++) {
                    $sillasReservadas[] = $j;
                }
            }
            
            $estado = $estados[array_rand($estados)];
            
            Reserva::create([
                'codigo_reserva' => 'MRK-' . strtoupper(Str::random(8)),
                'user_id' => $cliente->id,
                'funcion_id' => $funcion->id,
                'mesa_id' => $mesa->id,
                'num_personas' => $numPersonas,
                'monto_total' => $montoTotal,
                'incluye_cena' => $incluyeCena,
                'es_vip' => $esVip,
                'estado' => $estado,
                'sillas_reservadas' => $sillasReservadas,
                'mesa_completa' => $numPersonas == $mesa->capacidad,
                'notas' => rand(0, 1) ? 'Reserva de prueba #' . ($i + 1) : null,
                'check_in_at' => $estado === 'confirmada' && rand(0, 1) ? now()->subHours(rand(1, 5)) : null,
            ]);
        }

        $this->command->info('✅ 20 reservas de prueba creadas exitosamente');
    }
}
