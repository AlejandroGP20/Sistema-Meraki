<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Funcion;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::with(['user', 'funcion', 'mesa']);

        if ($request->user()->hasRole('cliente')) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->orderBy('created_at', 'desc')->get();

        return response()->json($reservas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|exists:funciones,id',
            'mesa_id' => 'required|exists:mesas,id',
            'num_personas' => 'required|integer|min:1',
            'incluye_cena' => 'boolean',
            'es_vip' => 'boolean',
            'notas' => 'nullable|string',
            'sillas_reservadas' => 'nullable|array',
            'sillas_reservadas.*' => 'integer|min:1|max:3',
            'mesa_completa' => 'boolean',
        ]);

        $funcion = Funcion::findOrFail($request->funcion_id);
        $mesa = Mesa::findOrFail($request->mesa_id);
        
        // Determinar sillas a reservar
        $sillasAReservar = [];
        $mesaCompleta = $request->mesa_completa ?? false;
        
        if ($mesa->capacidad == 1) {
            // Taburete - solo una silla
            $sillasAReservar = [1];
        } elseif ($mesaCompleta) {
            // Mesa completa - todas las sillas
            $sillasAReservar = range(1, $mesa->capacidad);
        } else {
            // Sillas específicas
            $sillasAReservar = $request->sillas_reservadas ?? [];
        }
        
        // Validar que se hayan seleccionado sillas
        if (empty($sillasAReservar)) {
            return response()->json(['message' => 'Debes seleccionar al menos una silla'], 422);
        }
        
        // Validar que el número de personas coincida con las sillas
        if (count($sillasAReservar) != $request->num_personas) {
            return response()->json(['message' => 'El número de personas debe coincidir con las sillas seleccionadas'], 422);
        }

        // Verificar disponibilidad de las sillas específicas
        $reservasExistentes = Reserva::where('funcion_id', $request->funcion_id)
            ->where('mesa_id', $request->mesa_id)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get();
        
        $sillasOcupadas = [];
        foreach ($reservasExistentes as $reserva) {
            if ($reserva->mesa_completa) {
                return response()->json(['message' => 'La mesa completa ya está reservada'], 422);
            }
            if ($reserva->sillas_reservadas) {
                $sillasOcupadas = array_merge($sillasOcupadas, $reserva->sillas_reservadas);
            }
        }
        
        // Verificar si alguna silla solicitada ya está ocupada
        $conflicto = array_intersect($sillasAReservar, $sillasOcupadas);
        if (!empty($conflicto)) {
            return response()->json([
                'message' => 'Las siguientes sillas ya están reservadas: ' . implode(', ', $conflicto)
            ], 422);
        }

        // Calcular precio por persona según elección del usuario
        if ($request->es_vip) {
            // Entrada VIP: 50 Bs por persona (incluye entrada + trago)
            $precioPorPersona = 50;
        } else {
            // Entrada Normal: 40 Bs por persona
            $precioPorPersona = 40;
        }
        
        // Si incluye cena, agregar 30 Bs por persona
        if ($request->incluye_cena) {
            $precioPorPersona += 30;
        }
        
        // Total
        $monto = $precioPorPersona * $request->num_personas;

        $reserva = Reserva::create([
            'codigo_reserva' => 'MRK-' . strtoupper(Str::random(8)),
            'user_id' => $request->user()->id,
            'funcion_id' => $request->funcion_id,
            'mesa_id' => $request->mesa_id,
            'num_personas' => $request->num_personas,
            'monto_total' => $monto,
            'incluye_cena' => $request->incluye_cena ?? false,
            'es_vip' => $request->es_vip ?? false,
            'notas' => $request->notas,
            'estado' => 'confirmada',
            'sillas_reservadas' => $sillasAReservar,
            'mesa_completa' => $mesaCompleta,
        ]);

        return response()->json($reserva->load(['funcion', 'mesa']), 201);
    }

    public function show(Reserva $reserva)
    {
        // Verificar que el usuario sea el dueño de la reserva o sea admin/encargado
        $user = auth()->user();
        
        if (!$user->hasRole(['admin', 'encargado']) && $user->id !== $reserva->user_id) {
            return response()->json(['message' => 'No tienes permiso para ver esta reserva'], 403);
        }
        
        return response()->json($reserva->load(['user', 'funcion.imagenes', 'mesa']));
    }

    public function cancel(Reserva $reserva)
    {
        // Verificar que el usuario sea el dueño de la reserva o sea admin/encargado
        $user = auth()->user();
        
        if (!$user->hasRole(['admin', 'encargado']) && $user->id !== $reserva->user_id) {
            return response()->json(['message' => 'No tienes permiso para cancelar esta reserva'], 403);
        }

        if ($reserva->estado === 'cancelada') {
            return response()->json(['message' => 'La reserva ya está cancelada'], 422);
        }

        $reserva->update(['estado' => 'cancelada']);

        return response()->json(['message' => 'Reserva cancelada exitosamente']);
    }

    public function checkIn(Request $request, Reserva $reserva)
    {
        if ($reserva->check_in_at) {
            return response()->json(['message' => 'Ya se realizó el check-in'], 422);
        }

        $reserva->update(['check_in_at' => now()]);

        return response()->json(['message' => 'Check-in realizado exitosamente']);
    }
}
