# 🔧 SOLUCIÓN: Portada de Fotos no se Refleja

## ❌ Problema
Las fotos se suben correctamente y se ven en la galería del admin, pero no se muestra la portada en la página de inicio ni en la sección de funciones.

## ✅ Solución Implementada

### 1. Cambios en el Modelo `Funcion.php`
Se agregó un **accessor** para que `imagen_principal` siempre esté disponible:

```php
protected $appends = ['imagen_principal'];

public function getImagenPrincipalAttribute()
{
    return $this->imagenes()->where('es_principal', true)->first();
}
```

Esto hace que cada vez que se cargue una función, automáticamente incluya la imagen principal en el JSON.

### 2. Auto-asignación de Portada
Se modificó `FuncionImagenController@store` para que:
- La **primera imagen** subida se marque automáticamente como principal
- Solo si la función no tiene ya una imagen principal

### 3. Mejora en `setPrincipal`
Se mejoró la función en el admin para que:
- Recargue completamente los datos después de marcar como principal
- Muestre errores detallados si algo falla

### 4. Script de Corrección
Se creó `fix-principal-images.php` para corregir funciones existentes que no tienen imagen principal.

---

## 🚀 Cómo Usar

### Para Nuevas Funciones
1. Ir a `/admin/funciones`
2. Click en "📷 Fotos"
3. Subir fotos
4. **La primera foto se marca automáticamente como portada** ⭐
5. Puedes cambiar la portada haciendo click en la estrella de otra foto

### Para Funciones Existentes (Sin Portada)
Ejecutar el script de corrección:
```bash
php fix-principal-images.php
```

Esto marcará automáticamente la primera imagen de cada función como portada.

### Cambiar Portada Manualmente
1. Ir a `/admin/funciones`
2. Click en "📷 Fotos" de la función
3. Click en la estrella (☆) de la foto que quieres como portada
4. Se marcará con estrella llena (⭐)
5. La portada se actualizará automáticamente en todo el sitio

---

## 🧪 Verificación

### 1. Verificar en Base de Datos
```bash
php artisan tinker
```

```php
// Ver todas las funciones con sus imágenes principales
\App\Models\Funcion::with('imagenes')->get()->map(function($f) {
    return [
        'id' => $f->id,
        'nombre' => $f->nombre,
        'tiene_principal' => $f->imagen_principal ? 'SÍ' : 'NO',
        'total_imagenes' => $f->imagenes->count()
    ];
});
```

### 2. Verificar en API
```bash
curl http://localhost:8000/api/funciones
```

Deberías ver en cada función:
```json
{
  "id": 1,
  "nombre": "Noche Indie",
  "imagen_principal": {
    "id": 1,
    "ruta": "funciones/abc123.jpg",
    "es_principal": true,
    "alt_text": "Noche Indie"
  },
  "imagenes": [...]
}
```

### 3. Verificar en Frontend
- **Home** (`/`): Debe mostrar la portada en las tarjetas de funciones
- **Funciones** (`/funciones`): Debe mostrar la portada en el grid
- **Detalle** (`/funcion/1`): Debe mostrar la portada en el hero

---

## 🐛 Troubleshooting

### La portada no se muestra después de marcarla
1. **Refrescar la página** (F5)
2. **Limpiar caché del navegador** (Ctrl+Shift+R)
3. Verificar en la consola del navegador si hay errores

### La estrella no cambia al hacer click
1. Abrir consola del navegador (F12)
2. Ver si hay errores en la pestaña "Console"
3. Verificar que el usuario tenga permisos de admin/encargado

### La imagen no se ve (404)
1. Verificar que el storage esté linkeado:
   ```bash
   php artisan storage:link
   ```
2. Verificar que la imagen exista en `storage/app/public/funciones/`

### Ninguna función tiene portada
Ejecutar el script de corrección:
```bash
php fix-principal-images.php
```

---

## 📝 Archivos Modificados

```
✅ app/Models/Funcion.php
✅ app/Http/Controllers/Api/FuncionImagenController.php
✅ resources/js/views/admin/Funciones.vue
✅ fix-principal-images.php (nuevo)
```

---

## 🎯 Resultado Esperado

Después de aplicar estos cambios:

1. ✅ Al subir fotos, la primera se marca automáticamente como portada
2. ✅ La portada se muestra en Home, Funciones y Detalle
3. ✅ Puedes cambiar la portada haciendo click en la estrella
4. ✅ Los cambios se reflejan inmediatamente en todo el sitio

---

## 💡 Notas Técnicas

### ¿Por qué usar un Accessor?
Laravel serializa las relaciones `hasOne` como objetos anidados, pero con el accessor `getImagenPrincipalAttribute()` y `$appends`, siempre se incluye automáticamente en el JSON sin necesidad de hacer `->load('imagenPrincipal')`.

### ¿Por qué auto-asignar la primera imagen?
Para mejorar la UX. Si el usuario sube fotos, es lógico que la primera sea la portada por defecto. Siempre puede cambiarla después.

### ¿Qué pasa si elimino la portada?
El sistema automáticamente marca la siguiente imagen (por orden) como nueva portada. Ver `FuncionImagenController@destroy`.

---

**Fecha**: 2026-03-06
**Estado**: ✅ RESUELTO
