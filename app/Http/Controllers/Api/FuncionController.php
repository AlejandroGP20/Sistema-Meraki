<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FuncionController extends Controller
{
    public function index(Request $request)
    {
        $query = Funcion::with(['imagenes', 'imagenPrincipal']);

        if ($request->has('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $funciones = $query->orderBy('fecha', 'asc')->get();

        return response()->json($funciones);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'horario' => 'required|date_format:H:i',
            'nombre' => 'required|string|max:255',
            'artista' => 'nullable|string|max:255',
            'genero_musical' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'imagenes.*' => 'nullable|image|max:2048',
        ]);

        $funcion = Funcion::create($request->except('imagenes'));

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('funciones', 'public');
                $funcion->imagenes()->create([
                    'ruta' => $path,
                    'orden' => $index,
                ]);
            }
        }

        return response()->json($funcion->load('imagenes'), 201);
    }

    public function show(Funcion $funcion)
    {
        return response()->json($funcion->load(['imagenes', 'imagenPrincipal', 'reservas']));
    }

    public function update(Request $request, Funcion $funcion)
    {
        $request->validate([
            'fecha' => 'sometimes|date',
            'horario' => 'sometimes|date_format:H:i',
            'nombre' => 'sometimes|string|max:255',
        ]);

        $funcion->update($request->except('imagenes'));

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('funciones', 'public');
                $funcion->imagenes()->create([
                    'ruta' => $path,
                    'orden' => $index,
                ]);
            }
        }

        return response()->json($funcion->load('imagenes'));
    }

    public function destroy(Funcion $funcion)
    {
        foreach ($funcion->imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->ruta);
        }
        
        $funcion->delete();

        return response()->json(['message' => 'Función eliminada exitosamente']);
    }
}
