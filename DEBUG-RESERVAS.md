# 🐛 DEBUG: Pantalla Blanca en Reservas

## Problema
Al acceder a `/admin/reservas` aparece pantalla blanca.

## Posibles Causas

### 1. Error de JavaScript
**Verificar**: Abrir consola del navegador (F12) y ver errores

### 2. Error de Autenticación
**Verificar**: 
```bash
# En consola del navegador
console.log(localStorage.getItem('token'))
```

### 3. Error en API
**Verificar**:
```bash
# Probar endpoint directamente
curl -H "Authorization: Bearer TU_TOKEN" http://localhost:8000/api/reservas
```

## Soluciones

### Solución 1: Limpiar Cache
```bash
# En el navegador
Ctrl + Shift + R (Windows/Linux)
Cmd + Shift + R (Mac)
```

### Solución 2: Verificar Token
1. Ir a `/login`
2. Iniciar sesión nuevamente
3. Volver a `/admin/reservas`

### Solución 3: Verificar Permisos
```bash
php artisan tinker
```
```php
// Verificar que el usuario tenga rol admin o encargado
$user = \App\Models\User::where('email', 'admin@meraki.com')->first();
$user->roles->pluck('name'); // Debe mostrar ['admin'] o ['encargado']
```

### Solución 4: Verificar Base de Datos
```bash
php artisan tinker
```
```php
// Verificar que haya reservas
\App\Models\Reserva::count();

// Ver una reserva de ejemplo
\App\Models\Reserva::with(['user', 'funcion', 'mesa'])->first();
```

### Solución 5: Reiniciar Servidor
```bash
# Detener servidor (Ctrl+C)
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Reiniciar
php artisan serve
```

## Pasos de Debugging

### 1. Abrir Consola del Navegador
- Presionar F12
- Ir a pestaña "Console"
- Buscar errores en rojo

### 2. Verificar Network
- En F12, ir a pestaña "Network"
- Recargar página
- Ver si hay peticiones fallidas (en rojo)
- Click en petición fallida para ver detalles

### 3. Verificar Respuesta de API
En consola del navegador:
```javascript
// Probar endpoint de reservas
fetch('/api/reservas', {
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('token'),
    'Accept': 'application/json'
  }
})
.then(r => r.json())
.then(data => console.log(data))
.catch(err => console.error(err));
```

### 4. Verificar Estadísticas
```javascript
// Probar endpoint de stats
fetch('/api/reservas-stats', {
  headers: {
    'Authorization': 'Bearer ' + localStorage.getItem('token'),
    'Accept': 'application/json'
  }
})
.then(r => r.json())
.then(data => console.log(data))
.catch(err => console.error(err));
```

## Errores Comunes

### Error 401 (No Autorizado)
**Causa**: Token expirado o inválido
**Solución**: Volver a iniciar sesión

### Error 403 (Prohibido)
**Causa**: Usuario no tiene permisos de admin/encargado
**Solución**: Verificar roles del usuario

### Error 500 (Error del Servidor)
**Causa**: Error en backend
**Solución**: Ver logs de Laravel
```bash
tail -f storage/logs/laravel.log
```

### Error de CORS
**Causa**: Problema de configuración
**Solución**: Verificar que estés accediendo desde `localhost:8000`

## Verificación Rápida

### Test 1: ¿El servidor está corriendo?
```bash
curl http://localhost:8000
```
Debe responder con HTML

### Test 2: ¿La API funciona?
```bash
curl http://localhost:8000/api/funciones
```
Debe responder con JSON

### Test 3: ¿Hay reservas en la BD?
```bash
php artisan tinker --execute="echo \App\Models\Reserva::count();"
```
Debe mostrar un número

### Test 4: ¿El build está actualizado?
```bash
npm run build
```
Debe completar sin errores

## Si Nada Funciona

### Opción 1: Usar Componente Anterior
Restaurar el componente anterior de Reservas.vue desde Git:
```bash
git checkout HEAD~1 -- resources/js/views/admin/Reservas.vue
npm run build
```

### Opción 2: Crear Reserva de Prueba
```bash
php artisan tinker
```
```php
$user = \App\Models\User::first();
$funcion = \App\Models\Funcion::first();
$mesa = \App\Models\Mesa::first();

\App\Models\Reserva::create([
    'codigo_reserva' => 'MRK-TEST001',
    'user_id' => $user->id,
    'funcion_id' => $funcion->id,
    'mesa_id' => $mesa->id,
    'num_personas' => 2,
    'monto_total' => 80,
    'incluye_cena' => false,
    'es_vip' => false,
    'estado' => 'confirmada',
    'sillas_reservadas' => [1, 2],
    'mesa_completa' => false,
]);
```

## Información para Reportar

Si el problema persiste, proporciona:
1. Mensaje de error en consola (F12)
2. Respuesta de `/api/reservas` (Network tab)
3. Versión de PHP: `php -v`
4. Versión de Node: `node -v`
5. Sistema operativo

---

**Última actualización**: 2026-03-06
