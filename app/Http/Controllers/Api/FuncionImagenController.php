<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcion;
use App\Models\FuncionImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Facades\Image; // TODO: Instalar intervention/image

class FuncionImagenController extends Controller
{
    /**
     * Subir múltiples imágenes
     */
    public function store(Request $request, Funcion $funcion)
    {
        $request->validate([
            'imagenes.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
            'alt_texts.*' => 'nullable|string|max:255',
        ]);

        $imagenes = [];
        $maxOrden = $funcion->imagenes()->max('orden') ?? -1;

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                // Guardar imagen original
                $path = $imagen->store('funciones', 'public');
                
                // Crear versiones optimizadas
                $this->createOptimizedVersions($path);

                $altText = $request->input("alt_texts.{$index}") ?? $funcion->nombre;
                
                $funcionImagen = $funcion->imagenes()->create([
                    'ruta' => $path,
                    'orden' => $maxOrden + $index + 1,
                    'es_principal' => false,
                    'alt_text' => $altText,
                ]);

                $imagenes[] = $funcionImagen;
            }
        }

        return response()->json([
            'message' => 'Imágenes subidas exitosamente',
            'imagenes' => $imagenes,
        ], 201);
    }

    /**
     * Reordenar imágenes
     */
    public function reorder(Request $request, Funcion $funcion)
    {
        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'required|integer|exists:funcion_imagenes,id',
        ]);

        foreach ($request->orden as $index => $imagenId) {
            FuncionImagen::where('id', $imagenId)
                ->where('funcion_id', $funcion->id)
                ->update(['orden' => $index]);
        }

        return response()->json([
            'message' => 'Orden actualizado exitosamente',
        ]);
    }

    /**
     * Marcar como principal
     */
    public function setPrincipal(Funcion $funcion, FuncionImagen $imagen)
    {
        // Quitar principal de todas las imágenes de esta función
        $funcion->imagenes()->update(['es_principal' => false]);

        // Marcar esta como principal
        $imagen->update(['es_principal' => true]);

        return response()->json([
            'message' => 'Imagen principal actualizada',
            'imagen' => $imagen,
        ]);
    }

    /**
     * Actualizar alt text
     */
    public function updateAltText(Request $request, FuncionImagen $imagen)
    {
        $request->validate([
            'alt_text' => 'required|string|max:255',
        ]);

        $imagen->update(['alt_text' => $request->alt_text]);

        return response()->json([
            'message' => 'Texto alternativo actualizado',
            'imagen' => $imagen,
        ]);
    }

    /**
     * Eliminar imagen
     */
    public function destroy(FuncionImagen $imagen)
    {
        // Eliminar archivos físicos
        Storage::disk('public')->delete($imagen->ruta);
        $this->deleteOptimizedVersions($imagen->ruta);

        // Si era la principal, marcar la primera como principal
        if ($imagen->es_principal) {
            $primeraImagen = FuncionImagen::where('funcion_id', $imagen->funcion_id)
                ->where('id', '!=', $imagen->id)
                ->orderBy('orden')
                ->first();
            
            if ($primeraImagen) {
                $primeraImagen->update(['es_principal' => true]);
            }
        }

        $imagen->delete();

        return response()->json([
            'message' => 'Imagen eliminada exitosamente',
        ]);
    }

    /**
     * Crear versiones optimizadas de la imagen
     * TODO: Descomentar cuando se instale intervention/image
     */
    private function createOptimizedVersions($path)
    {
        // Temporalmente deshabilitado - instalar: composer require intervention/image
        return;
        
        /*
        $fullPath = storage_path('app/public/' . $path);
        $directory = dirname($fullPath);
        $filename = pathinfo($fullPath, PATHINFO_FILENAME);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        // Thumbnail (300x200)
        $thumbnailPath = "{$directory}/{$filename}_thumb.{$extension}";
        Image::make($fullPath)
            ->fit(300, 200)
            ->save($thumbnailPath, 80);

        // Medium (800x600)
        $mediumPath = "{$directory}/{$filename}_medium.{$extension}";
        Image::make($fullPath)
            ->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($mediumPath, 85);
        */
    }

    /**
     * Eliminar versiones optimizadas
     * TODO: Descomentar cuando se instale intervention/image
     */
    private function deleteOptimizedVersions($path)
    {
        // Temporalmente deshabilitado
        return;
        
        /*
        $pathInfo = pathinfo($path);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        Storage::disk('public')->delete("{$directory}/{$filename}_thumb.{$extension}");
        Storage::disk('public')->delete("{$directory}/{$filename}_medium.{$extension}");
        */
    }
}
