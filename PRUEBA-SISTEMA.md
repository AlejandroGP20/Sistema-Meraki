# 🧪 GUÍA DE PRUEBA DEL SISTEMA

## ✅ Estado Actual

### Servidores:
- ✅ Laravel: http://127.0.0.1:8000
- ✅ Vite: http://localhost:5173
- ✅ MySQL: Corriendo
- ✅ Base de datos: `meraki_reservas` con 25 mesas

---

## 🎯 PRUEBA COMPLETA PASO A PASO

### PASO 1: Crear una Función (Como ADMIN)

1. Abre el navegador en: **http://127.0.0.1:8000**
2. Click en **"Iniciar Sesión"**
3. Login:
   - Email: `admin@meraki.com`
   - Password: `password`
4. Click en **"Admin"** en el menú superior
5. Click en **"Funciones"** en el sidebar
6. Click en **"+ Nueva Función"**
7. Llenar el formulario:
   ```
   Nombre: Noche de Jazz
   Artista: Los Jazzeros
   Género Musical: Jazz
   Fecha: [Selecciona un viernes próximo]
   Horario: 19:00
   ```
8. Click **"Guardar"**
9. ✅ Deberías ver la función creada en la lista

---

### PASO 2: Ver las Funciones (Como PÚBLICO)

1. Click en **"Salir"** (botón rojo en el menú)
2. Click en **"Funciones"** en el menú
3. ✅ Deberías ver la función "Noche de Jazz" que acabas de crear
4. ✅ Verás el botón **"Reservar"**

---

### PASO 3: Hacer una Reserva (Como CLIENTE)

#### Opción A: Con usuario existente
1. Click en **"Iniciar Sesión"**
2. Login:
   - Email: `cliente@demo.com`
   - Password: `password`

#### Opción B: Crear nuevo usuario
1. Click en **"Registrarse"**
2. Llenar el formulario:
   ```
   Nombre: Tu Nombre
   Email: tunombre@email.com
   Contraseña: password
   Confirmar Contraseña: password
   ```
3. Click **"Registrarse"**

#### Continuar con la reserva:
4. Ir a **"Funciones"**
5. Click en **"Reservar"** en la función "Noche de Jazz"
6. ✅ **Deberías ver el mapa de mesas** con:
   - Mesas verdes (disponibles)
   - Mesas con borde morado (VIP)
   - Diferentes formas:
     * ⭕ Círculos = Zona Escenario (Mesas 1-8)
     * ⬜ Cuadrados = Zona Barra (Mesas 9-16)
     * ⬡ Hexágonos = Zona Plaza (Mesas 17-25)

7. **Click en cualquier mesa verde**
8. Se abrirá un modal con:
   - Número de mesa
   - Zona
   - Capacidad
   - Opción de incluir cena
   - Total a pagar

9. Selecciona:
   - Número de personas: 2
   - ☑️ Incluir cena (opcional)

10. Click **"Confirmar"**
11. ✅ Deberías ver el mensaje "¡Reserva confirmada exitosamente!"
12. Serás redirigido a **"Mis Reservas"**

---

### PASO 4: Ver Mis Reservas (Como CLIENTE)

1. En **"Mis Reservas"** deberías ver:
   - Código de reserva (ej: MRK-ABC12345)
   - Función: Noche de Jazz
   - Fecha y horario
   - Mesa número
   - Número de personas
   - Total pagado
   - Estado: **confirmada** (verde)
   - Botón **"Cancelar Reserva"**

---

### PASO 5: Gestionar Reservas (Como ENCARGADO)

1. Click en **"Salir"**
2. Click en **"Iniciar Sesión"**
3. Login:
   - Email: `encargado@meraki.com`
   - Password: `password`
4. Click en **"Admin"**
5. Click en **"Reservas"** en el sidebar
6. ✅ Deberías ver TODAS las reservas del sistema:
   - Código de reserva
   - Cliente (nombre del usuario)
   - Función
   - Mesa
   - Personas
   - Total
   - Estado
   - Botones: **"Check-in"** y **"Cancelar"**

7. Click en **"Check-in"** en una reserva
8. ✅ Deberías ver "Check-in realizado"

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Problema: No veo mesas en el mapa

**Solución:**
1. Abre la consola del navegador (F12)
2. Ve a la pestaña "Console"
3. Busca errores en rojo
4. Verifica que veas: "Mesas cargadas: [Array con 25 mesas]"

Si no ves las mesas:
```bash
cd meraki-reservas
php artisan tinker --execute="echo App\Models\Mesa::count();"
```
Debería mostrar: `25`

---

### Problema: Error al crear función

**Solución:**
```bash
cd meraki-reservas
php artisan config:clear
php artisan cache:clear
```
Luego recarga la página (F5)

---

### Problema: Error "No autenticado"

**Solución:**
1. Cierra sesión completamente
2. Limpia las cookies del navegador (F12 → Application → Cookies → Eliminar todo)
3. Vuelve a iniciar sesión

---

### Problema: Las mesas aparecen todas como reservadas

**Solución:**
Puede que hayas hecho muchas reservas de prueba. Resetea la base de datos:
```bash
cd meraki-reservas
php artisan migrate:fresh --seed
```
⚠️ Esto borrará todas las reservas y funciones creadas

---

## 📊 VERIFICAR DATOS EN LA BASE DE DATOS

### Ver mesas:
```bash
php artisan tinker --execute="App\Models\Mesa::all(['numero', 'zona', 'capacidad'])->take(5);"
```

### Ver funciones:
```bash
php artisan tinker --execute="App\Models\Funcion::all(['nombre', 'fecha', 'horario']);"
```

### Ver reservas:
```bash
php artisan tinker --execute="App\Models\Reserva::with('user', 'mesa')->get();"
```

### Ver usuarios:
```bash
php artisan tinker --execute="App\Models\User::all(['name', 'email']);"
```

---

## 🎨 CARACTERÍSTICAS DEL MAPA DE MESAS

### Colores:
- 🟢 **Verde**: Mesa disponible (puedes hacer click)
- 🔴 **Rojo**: Mesa reservada (no disponible)
- 💜 **Borde morado**: Mesa VIP (+50 Bs adicional)

### Formas:
- ⭕ **Círculo**: Zona Escenario (Mesas 1-8)
  - Mesas 2, 4, 8 son VIP
- ⬜ **Cuadrado**: Zona Barra (Mesas 9-16)
  - Todas para 2 personas
- ⬡ **Hexágono**: Zona Plaza (Mesas 17-25)
  - Mesas 20 y 25 son VIP (6 personas)

### Interacción:
- **Hover**: La mesa se agranda ligeramente
- **Click**: Abre modal de confirmación
- **Info**: Muestra número, zona, capacidad

---

## 💰 CÁLCULO DE PRECIOS

### Precios Base:
- Entrada general: **40 Bs**
- Entrada + cena: **70 Bs**
- Mesa VIP (adicional): **+50 Bs**

### Ejemplos:
1. Mesa estándar sin cena: **40 Bs**
2. Mesa estándar con cena: **70 Bs**
3. Mesa VIP sin cena: **90 Bs** (40 + 50)
4. Mesa VIP con cena: **120 Bs** (70 + 50)

---

## ✅ CHECKLIST DE FUNCIONALIDADES

- [ ] Login como admin
- [ ] Crear función
- [ ] Ver función en lista pública
- [ ] Registrar nuevo cliente
- [ ] Ver mapa de mesas (25 mesas visibles)
- [ ] Seleccionar mesa disponible
- [ ] Confirmar reserva
- [ ] Ver "Mis Reservas"
- [ ] Login como encargado
- [ ] Ver todas las reservas
- [ ] Hacer check-in
- [ ] Cancelar reserva

---

¡Si completaste todos los pasos, el sistema está funcionando perfectamente! 🎉
