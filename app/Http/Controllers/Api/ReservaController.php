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

        // Filtro por rol
        if ($request->user()->hasRole('cliente')) {
            $query->where('user_id', $request->user()->id);
        }

        // Filtros avanzados para admin/encargado
        if ($request->has('funcion_id')) {
            $query->where('funcion_id', $request->funcion_id);
        }

        if ($request->has('mesa_id')) {
            $query->where('mesa_id', $request->mesa_id);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha_desde')) {
            $query->whereHas('funcion', function($q) use ($request) {
                $q->whereDate('fecha', '>=', $request->fecha_desde);
            });
        }

        if ($request->has('fecha_hasta')) {
            $query->whereHas('funcion', function($q) use ($request) {
                $q->whereDate('fecha', '<=', $request->fecha_hasta);
            });
        }

        if ($request->has('es_vip')) {
            $query->where('es_vip', $request->es_vip === 'true' || $request->es_vip === '1');
        }

        if ($request->has('incluye_cena')) {
            $query->where('incluye_cena', $request->incluye_cena === 'true' || $request->incluye_cena === '1');
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo_reserva', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        $perPage = $request->get('per_page', 15);
        $reservas = $query->paginate($perPage);

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

    /**
     * Confirmar reserva (admin/encargado)
     */
    public function confirm(Reserva $reserva)
    {
        if ($reserva->estado === 'confirmada') {
            return response()->json(['message' => 'La reserva ya está confirmada'], 422);
        }

        $reserva->update(['estado' => 'confirmada']);

        return response()->json([
            'message' => 'Reserva confirmada exitosamente',
            'reserva' => $reserva->load(['user', 'funcion', 'mesa'])
        ]);
    }

    /**
     * Marcar como no-show (admin/encargado)
     */
    public function noShow(Reserva $reserva)
    {
        if ($reserva->estado === 'no_show') {
            return response()->json(['message' => 'La reserva ya está marcada como no-show'], 422);
        }

        $reserva->update(['estado' => 'no_show']);

        return response()->json([
            'message' => 'Reserva marcada como no-show',
            'reserva' => $reserva->load(['user', 'funcion', 'mesa'])
        ]);
    }

    /**
     * Estadísticas para dashboard
     */
    public function stats(Request $request)
    {
        $query = Reserva::query();

        // Filtro por fecha
        if ($request->has('fecha_desde')) {
            $query->whereHas('funcion', function($q) use ($request) {
                $q->whereDate('fecha', '>=', $request->fecha_desde);
            });
        }

        if ($request->has('fecha_hasta')) {
            $query->whereHas('funcion', function($q) use ($request) {
                $q->whereDate('fecha', '<=', $request->fecha_hasta);
            });
        }

        $stats = [
            'total_reservas' => (clone $query)->count(),
            'confirmadas' => (clone $query)->where('estado', 'confirmada')->count(),
            'pendientes' => (clone $query)->where('estado', 'pendiente')->count(),
            'canceladas' => (clone $query)->where('estado', 'cancelada')->count(),
            'no_show' => (clone $query)->where('estado', 'no_show')->count(),
            'con_check_in' => (clone $query)->whereNotNull('check_in_at')->count(),
            'total_personas' => (clone $query)->whereIn('estado', ['confirmada', 'pendiente'])->sum('num_personas'),
            'ingresos_totales' => (clone $query)->whereIn('estado', ['confirmada'])->sum('monto_total'),
            'ingresos_vip' => (clone $query)->where('estado', 'confirmada')->where('es_vip', true)->sum('monto_total'),
            'reservas_con_cena' => (clone $query)->where('incluye_cena', true)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Exportar reservas (CSV)
     */
    public function export(Request $request)
    {
        $query = Reserva::with(['user', 'funcion', 'mesa']);

        // Aplicar mismos filtros que index
        if ($request->has('funcion_id')) {
            $query->where('funcion_id', $request->funcion_id);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha_desde')) {
            $query->whereHas('funcion', function($q) use ($request) {
                $q->whereDate('fecha', '>=', $request->fecha_desde);
            });
        }

        if ($request->has('fecha_hasta')) {
            $query->whereHas('funcion', function($q) use ($request) {
                $q->whereDate('fecha', '<=', $request->fecha_hasta);
            });
        }

        $reservas = $query->orderBy('created_at', 'desc')->get();

        $csv = "Código,Cliente,Email,Función,Fecha,Mesa,Personas,VIP,Cena,Monto,Estado,Check-in\n";
        
        foreach ($reservas as $reserva) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%d,%s,%s,%.2f,%s,%s\n",
                $reserva->codigo_reserva,
                $reserva->user->name,
                $reserva->user->email,
                $reserva->funcion->nombre,
                $reserva->funcion->fecha->format('Y-m-d'),
                "Mesa {$reserva->mesa->numero}",
                $reserva->num_personas,
                $reserva->es_vip ? 'Sí' : 'No',
                $reserva->incluye_cena ? 'Sí' : 'No',
                $reserva->monto_total,
                $reserva->estado,
                $reserva->check_in_at ? $reserva->check_in_at->format('Y-m-d H:i') : 'No'
            );
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="reservas_' . date('Y-m-d') . '.csv"');
    }
}
