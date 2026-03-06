# 🚀 INICIO RÁPIDO - MERAKI Sistema de Reservas

## ✅ Estado del Sistema

### Servidores Activos:
- ✅ **Laravel Backend**: http://127.0.0.1:8000
- ✅ **Vite Frontend**: http://localhost:5173

### Base de Datos:
- ✅ Migraciones ejecutadas
- ✅ 25 mesas creadas
- ✅ Roles y permisos configurados
- ✅ 3 usuarios de prueba creados

---

## 🎯 ACCESO AL SISTEMA

### URL Principal:
**http://127.0.0.1:8000**

---

## 👥 USUARIOS DE PRUEBA

### 1️⃣ ADMIN (Acceso Total)
```
Email: admin@meraki.com
Password: password
```
**Puede hacer:**
- Ver dashboard con estadísticas
- Gestionar funciones (crear, editar, eliminar)
- Ver y gestionar todas las reservas
- Realizar check-in
- Acceso a configuración del sistema

---

### 2️⃣ ENCARGADO (Gestión Operativa)
```
Email: encargado@meraki.com
Password: password
```
**Puede hacer:**
- Ver dashboard operativo
- Gestionar funciones (crear, editar, eliminar)
- Ver y gestionar reservas
- Realizar check-in de clientes
- NO puede gestionar usuarios

---

### 3️⃣ CLIENTE (Usuario Final)
```
Email: cliente@demo.com
Password: password
```
**Puede hacer:**
- Ver calendario de funciones
- Reservar mesas usando el mapa interactivo
- Ver historial de sus reservas
- Cancelar sus propias reservas

---

## 🧪 FLUJO DE PRUEBA RECOMENDADO

### Paso 1: Probar como ADMIN
1. Ir a http://127.0.0.1:8000
2. Click en "Iniciar Sesión"
3. Login con: `admin@meraki.com` / `password`
4. Click en "Admin" en el menú
5. Ir a "Funciones" → Click "Nueva Función"
6. Crear una función:
   - Nombre: "Noche de Jazz"
   - Artista: "Los Jazzeros"
   - Género: "Jazz"
   - Fecha: Selecciona un viernes próximo
   - Horario: 19:00
7. Click "Guardar"

### Paso 2: Probar como CLIENTE
1. Cerrar sesión (botón "Salir")
2. Click en "Funciones" en el menú
3. Verás la función que creaste
4. Click en "Reservar"
5. **MAPA INTERACTIVO**:
   - 🟢 Verde = Disponible (puedes hacer click)
   - 🔴 Rojo = Reservada (no disponible)
   - 💜 Borde morado = Mesa VIP (+50 Bs)
   - Formas:
     * ⭕ Círculos = Zona Escenario (Mesas 1-8)
     * ⬜ Cuadrados = Zona Barra (Mesas 9-16)
     * ⬡ Hexágonos = Zona Plaza (Mesas 17-25)
6. Click en una mesa verde disponible
7. Se abrirá un modal:
   - Selecciona número de personas
   - Marca "Incluir cena" si deseas (+30 Bs)
   - Verás el total calculado
8. Click "Confirmar"
9. Ir a "Mis Reservas" para ver tu reserva

### Paso 3: Probar Check-in como ENCARGADO
1. Cerrar sesión
2. Login con: `encargado@meraki.com` / `password`
3. Ir a "Admin" → "Reservas"
4. Verás todas las reservas del sistema
5. Click en "Check-in" para marcar asistencia
6. El sistema registrará la hora de check-in

---

## 🗺️ MAPA DE MESAS (25 mesas)

### Zona Escenario (Mesas 1-8) - ⭕ Círculos
- Mesa 2: VIP (4 personas)
- Mesa 4: VIP (4 personas)
- Mesa 8: VIP (4 personas)
- Resto: Estándar (2-4 personas)

### Zona Barra (Mesas 9-16) - ⬜ Cuadrados
- Todas: 2 personas
- Tipo: Barra

### Zona Plaza (Mesas 17-25) - ⬡ Hexágonos
- Mesa 20: VIP (6 personas)
- Mesa 25: VIP (6 personas)
- Resto: Estándar (4 personas)

---

## 💰 PRECIOS

- **Entrada General**: 40 Bs
- **Entrada + Cena**: 70 Bs
- **Mesa VIP** (adicional): +50 Bs

**Ejemplo de cálculo:**
- Mesa estándar + entrada = 40 Bs
- Mesa estándar + entrada + cena = 70 Bs
- Mesa VIP + entrada = 90 Bs
- Mesa VIP + entrada + cena = 120 Bs

---

## 🔧 COMANDOS ÚTILES

### Ver logs de Laravel:
```bash
cd meraki-reservas
php artisan tail
```

### Ver estado de los servidores:
- Laravel: http://127.0.0.1:8000/up
- Vite: http://localhost:5173

### Reiniciar base de datos (CUIDADO: borra todo):
```bash
php artisan migrate:fresh --seed
```

### Detener servidores:
Presiona `Ctrl+C` en cada terminal

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "SQLSTATE[HY000]"
```bash
php artisan migrate:fresh --seed
```

### Error: "Vite manifest not found"
```bash
npm run build
```

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Puerto 8000 ocupado:
```bash
php artisan serve --port=8001
```
Luego accede a http://127.0.0.1:8001

---

## 📱 FUNCIONALIDADES IMPLEMENTADAS

✅ Sistema de autenticación (registro/login)
✅ 3 roles con permisos diferenciados
✅ CRUD completo de funciones
✅ Mapa interactivo de 25 mesas con Konva.js
✅ Sistema de reservas en tiempo real
✅ Estados de mesa (disponible/reservada)
✅ Cálculo automático de precios
✅ Historial de reservas por usuario
✅ Cancelación de reservas
✅ Check-in de reservas
✅ Dashboard con estadísticas
✅ Gestión de reservas para admin/encargado
✅ Validación de disponibilidad
✅ Responsive design

---

## 🎨 TECNOLOGÍAS USADAS

**Backend:**
- Laravel 12
- Spatie Laravel Permission
- Laravel Sanctum
- SQLite

**Frontend:**
- Vue 3 (Composition API)
- Vue Router 4
- Pinia
- Konva.js + vue-konva
- Axios
- Vite

---

## 📞 SOPORTE

Si encuentras algún problema:
1. Revisa los logs en `storage/logs/laravel.log`
2. Verifica que ambos servidores estén corriendo
3. Limpia caché: `php artisan cache:clear`

---

¡Disfruta probando el sistema MERAKI! 🎭✨
