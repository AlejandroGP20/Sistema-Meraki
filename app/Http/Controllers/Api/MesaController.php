<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mesa;
use App\Models\Reserva;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        $mesas = Mesa::all();
        return response()->json($mesas);
    }

    public function disponibilidad(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|exists:funciones,id',
        ]);

        $mesas = Mesa::all();
        
        // Obtener todas las reservas activas para esta función
        $reservas = Reserva::where('funcion_id', $request->funcion_id)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get();

        $mesasConEstado = $mesas->map(function ($mesa) use ($reservas) {
            // Encontrar reservas para esta mesa
            $reservasMesa = $reservas->where('mesa_id', $mesa->id);
            
            // Para taburetes (capacidad 1)
            if ($mesa->capacidad == 1) {
                $estado = $reservasMesa->isNotEmpty() ? 'reservada' : 'disponible';
                return [
                    'id' => $mesa->id,
                    'numero' => $mesa->numero,
                    'zona' => $mesa->zona,
                    'capacidad' => $mesa->capacidad,
                    'tipo' => $mesa->tipo,
                    'es_vip' => $mesa->es_vip,
                    'forma' => $mesa->forma,
                    'coord_x' => $mesa->coord_x,
                    'coord_y' => $mesa->coord_y,
                    'estado' => $estado,
                    'sillas_disponibles' => $estado === 'disponible' ? [1] : [],
                    'sillas_ocupadas' => $estado === 'reservada' ? [1] : [],
                ];
            }
            
            // Para mesas con múltiples sillas
            $sillasOcupadas = [];
            $mesaCompleta = false;
            
            foreach ($reservasMesa as $reserva) {
                if ($reserva->mesa_completa) {
                    $mesaCompleta = true;
                    $sillasOcupadas = [1, 2, 3];
                    break;
                }
                if ($reserva->sillas_reservadas) {
                    $sillasOcupadas = array_merge($sillasOcupadas, $reserva->sillas_reservadas);
                }
            }
            
            $sillasOcupadas = array_unique($sillasOcupadas);
            $todasLasSillas = range(1, $mesa->capacidad);
            $sillasDisponibles = array_values(array_diff($todasLasSillas, $sillasOcupadas));
            
            $estado = empty($sillasDisponibles) ? 'reservada' : 'disponible';
            
            return [
                'id' => $mesa->id,
                'numero' => $mesa->numero,
                'zona' => $mesa->zona,
                'capacidad' => $mesa->capacidad,
                'tipo' => $mesa->tipo,
                'es_vip' => $mesa->es_vip,
                'forma' => $mesa->forma,
                'coord_x' => $mesa->coord_x,
                'coord_y' => $mesa->coord_y,
                'estado' => $estado,
                'sillas_disponibles' => $sillasDisponibles,
                'sillas_ocupadas' => array_values($sillasOcupadas),
                'mesa_completa' => $mesaCompleta,
            ];
        });

        return response()->json($mesasConEstado);
    }
}
