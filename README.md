# 🎭 MERAKI Teatro Bar - Sistema de Reservas MVP

Sistema completo de reservas para teatro bar con gestión de funciones, mesas interactivas y roles de usuario.

## 🚀 Características Principales

### 👥 Roles de Usuario
- **Admin**: Gestión completa del sistema, usuarios, reportes y configuración
- **Encargado**: CRUD de funciones, gestión de reservas, check-in QR
- **Cliente**: Registro, reservas, historial, cancelaciones

### 🎪 Módulo de Funciones
- Calendario de eventos (jueves, viernes, sábado)
- Horarios: 19:00, 21:30
- Galería de imágenes
- Precios configurables:
  - Entrada general: 40 Bs
  - Entrada + cena: 70 Bs
  - Mesa VIP: +50 Bs

### 🗺️ Mapa Interactivo de Mesas (25 mesas)
- **Zona Escenario** (Mesas 1-8): Círculos
- **Zona Barra** (Mesas 9-16): Cuadrados
- **Zona Plaza** (Mesas 17-25): Hexágonos
- Estados en tiempo real: Disponible 🟢 | Reservada 🔴 | VIP 💜
- Selección visual con Konva.js

## 📋 Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (incluido) o MySQL

## 🛠️ Instalación

### 1. Clonar e instalar dependencias

```bash
cd meraki-reservas
composer install
npm install
```

### 2. Configurar base de datos

El proyecto usa SQLite por defecto. Si prefieres MySQL, edita `.env`:

```env
DB_CONNECTION=sqlite
# O para MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=meraki
# DB_USERNAME=root
# DB_PASSWORD=
```

### 3. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto creará:
- 3 usuarios de prueba
- 25 mesas configuradas
- Roles y permisos

### 4. Iniciar servidores

Terminal 1 - Laravel:
```bash
php artisan serve
```

Terminal 2 - Vite (Vue):
```bash
npm run dev
```

### 5. Acceder al sistema

- **URL**: http://localhost:8000
- **Admin**: admin@meraki.com / password
- **Encargado**: encargado@meraki.com / password
- **Cliente**: cliente@demo.com / password

## 📁 Estructura del Proyecto

```
meraki-reservas/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── FuncionController.php
│   │   ├── MesaController.php
│   │   └── ReservaController.php
│   ├── Models/
│   │   ├── Funcion.php
│   │   ├── Mesa.php
│   │   ├── Reserva.php
│   │   └── User.php
│   └── Policies/
│       └── ReservaPolicy.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── views/
│   │   │   ├── Home.vue
│   │   │   ├── Login.vue
│   │   │   ├── Register.vue
│   │   │   ├── Funciones.vue
│   │   │   ├── Reservar.vue (Mapa interactivo)
│   │   │   ├── MisReservas.vue
│   │   │   └── admin/
│   │   │       ├── Dashboard.vue
│   │   │       ├── Funciones.vue
│   │   │       └── Reservas.vue
│   │   ├── stores/
│   │   │   └── auth.js
│   │   ├── router/
│   │   │   └── index.js
│   │   └── App.vue
│   └── views/
│       └── welcome.blade.php
└── routes/
    ├── api.php
    └── web.php
```

## 🎯 Funcionalidades por Rol

### Cliente
- ✅ Registro y login
- ✅ Ver calendario de funciones
- ✅ Mapa interactivo 2D con estados en tiempo real
- ✅ Seleccionar mesa y reservar
- ✅ Historial de reservas
- ✅ Cancelar reservas

### Encargado
- ✅ Todo lo del cliente +
- ✅ CRUD completo de funciones
- ✅ Gestión de reservas (confirmar, cancelar, modificar)
- ✅ Check-in de reservas
- ✅ Dashboard operativo

### Admin
- ✅ Todo lo del encargado +
- ✅ Gestión de usuarios
- ✅ Reportes y estadísticas
- ✅ Configuración del sistema

## 🔌 API Endpoints

### Autenticación
- `POST /api/register` - Registro de cliente
- `POST /api/login` - Iniciar sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/me` - Usuario actual

### Funciones
- `GET /api/funciones` - Listar funciones
- `GET /api/funciones/{id}` - Detalle de función
- `POST /api/funciones` - Crear función (admin/encargado)
- `PUT /api/funciones/{id}` - Actualizar función
- `DELETE /api/funciones/{id}` - Eliminar función

### Mesas
- `GET /api/mesas` - Listar todas las mesas
- `GET /api/mesas/disponibilidad?funcion_id={id}` - Disponibilidad por función

### Reservas
- `GET /api/reservas` - Listar reservas
- `POST /api/reservas` - Crear reserva
- `GET /api/reservas/{id}` - Detalle de reserva
- `POST /api/reservas/{id}/cancel` - Cancelar reserva
- `POST /api/reservas/{id}/check-in` - Check-in

## 🎨 Stack Tecnológico

### Backend
- Laravel 12
- Spatie Laravel Permission (roles y permisos)
- Laravel Sanctum (autenticación API)
- Laravel DomPDF (exportar reportes)
- SQLite/MySQL

### Frontend
- Vue 3 (Composition API)
- Vue Router 4
- Pinia (state management)
- Konva.js + vue-konva (mapa interactivo)
- Axios (HTTP client)
- Vite (build tool)

## 📊 Base de Datos

### Tablas Principales
- `users` - Usuarios del sistema
- `roles` y `permissions` - Sistema de roles (Spatie)
- `funciones` - Eventos/shows
- `funcion_imagenes` - Galería de imágenes
- `mesas` - 25 mesas con coordenadas
- `reservas` - Reservas de clientes
- `activity_logs` - Logs de actividad

## 🔐 Seguridad

- Autenticación con Laravel Sanctum
- Autorización basada en roles (Spatie Permission)
- Políticas de acceso (ReservaPolicy)
- Validación de datos en backend
- Protección CSRF
- Middleware de autenticación

## 🚧 Próximas Mejoras (Post-MVP)

- [ ] Generación de códigos QR para reservas
- [ ] Escaneo QR para check-in
- [ ] Exportar reportes a PDF/Excel
- [ ] Notificaciones por email
- [ ] Integración con pasarelas de pago
- [ ] Compartir eventos en redes sociales
- [ ] Exportar a Google Calendar
- [ ] Sistema de cupones y descuentos
- [ ] Reseñas y calificaciones

## 📝 Notas de Desarrollo

- El mapa de mesas usa coordenadas fijas definidas en el seeder
- Los estados de mesa se calculan en tiempo real consultando reservas activas
- Las mesas VIP tienen un cargo adicional de 50 Bs
- Solo se permiten reservas para jueves, viernes y sábado
- Los horarios disponibles son 19:00 y 21:30

## 🤝 Contribuir

Este es un MVP funcional. Para mejoras:
1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## 📄 Licencia

Proyecto privado para MERAKI Teatro Bar.

---

Desarrollado con ❤️ para MERAKI Teatro Bar
