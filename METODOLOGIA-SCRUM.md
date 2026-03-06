# 🏃 METODOLOGÍA SCRUM - SISTEMA MERAKI
## Desarrollo en 4 Sprints

---

## 📋 INFORMACIÓN GENERAL DEL PROYECTO

**Proyecto:** Sistema de Reservas MERAKI Teatro Bar
**Metodología:** SCRUM
**Duración Total:** 4 Sprints (4 semanas)
**Duración por Sprint:** 1 semana (7 días)
**Equipo:** 1 Desarrollador Full Stack

---

## 🎯 PRODUCT BACKLOG PRIORIZADO

### Épicas Principales

1. **Autenticación y Usuarios** (Prioridad: Alta)
2. **Gestión de Funciones** (Prioridad: Alta)
3. **Sistema de Reservas** (Prioridad: Crítica)
4. **Mapa Interactivo** (Prioridad: Crítica)
5. **Panel Administrativo** (Prioridad: Alta)
6. **Página Pública** (Prioridad: Media)
7. **Responsive Design** (Prioridad: Alta)

---

## 🏃 SPRINT 1: FUNDAMENTOS Y AUTENTICACIÓN
**Duración:** 7 días
**Objetivo:** Establecer la base del sistema con autenticación funcional

### 📊 Sprint Planning

**Story Points Totales:** 21 puntos
**Velocidad Estimada:** 21 puntos/sprint

### 📝 User Stories

#### US-001: Configuración del Proyecto (3 pts)
**Como** desarrollador
**Quiero** configurar el entorno de desarrollo
**Para** poder comenzar a desarrollar el sistema

**Criterios de Aceptación:**
- ✅ Laravel 12 instalado y configurado
- ✅ Vue 3 + Vite configurado
- ✅ Base de datos MySQL creada
- ✅ Git repository inicializado
- ✅ Dependencias instaladas

**Tareas:**
- [ ] Instalar Laravel 12
- [ ] Configurar Vue 3 con Vite
- [ ] Crear base de datos
- [ ] Configurar .env
- [ ] Instalar Tailwind CSS

---

#### US-002: Sistema de Autenticación (5 pts)
**Como** usuario
**Quiero** poder registrarme e iniciar sesión
**Para** acceder al sistema

**Criterios de Aceptación:**
- ✅ Registro de usuarios funcional
- ✅ Login con email y contraseña
- ✅ Logout funcional
- ✅ Tokens de sesión (Sanctum)
- ✅ Validación de formularios

**Tareas:**
- [ ] Instalar Laravel Sanctum
- [ ] Crear AuthController
- [ ] Crear componentes Vue (Login, Register)
- [ ] Implementar Pinia store para auth
- [ ] Proteger rutas

---

#### US-003: Sistema de Roles (5 pts)
**Como** administrador
**Quiero** que existan diferentes roles
**Para** controlar el acceso al sistema

**Criterios de Aceptación:**
- ✅ Roles: Admin, Encargado, Cliente
- ✅ Permisos por rol definidos
- ✅ Middleware de verificación
- ✅ Asignación de roles en registro

**Tareas:**
- [ ] Instalar Spatie Permission
- [ ] Crear migraciones de roles
- [ ] Crear seeder de roles
- [ ] Implementar middleware CheckRole
- [ ] Configurar permisos

---

#### US-004: Modelos Base (5 pts)
**Como** desarrollador
**Quiero** crear los modelos principales
**Para** estructurar la base de datos

**Criterios de Aceptación:**
- ✅ Modelo User con roles
- ✅ Modelo Funcion
- ✅ Modelo Mesa
- ✅ Modelo Reserva
- ✅ Relaciones definidas

**Tareas:**
- [ ] Crear migraciones
- [ ] Crear modelos Eloquent
- [ ] Definir relaciones
- [ ] Crear factories
- [ ] Crear seeders básicos

---

#### US-005: Routing y Navegación (3 pts)
**Como** usuario
**Quiero** navegar entre páginas
**Para** usar el sistema

**Criterios de Aceptación:**
- ✅ Vue Router configurado
- ✅ Rutas públicas y privadas
- ✅ Guards de navegación
- ✅ Redirecciones según rol

**Tareas:**
- [ ] Configurar Vue Router
- [ ] Crear rutas principales
- [ ] Implementar guards
- [ ] Crear layout base

---

### 📈 Sprint Review

**Entregables:**
- ✅ Sistema de autenticación completo
- ✅ Roles y permisos funcionando
- ✅ Base de datos estructurada
- ✅ Navegación básica

**Demo:**
- Registro de usuario
- Login/Logout
- Verificación de roles

---

### 🔄 Sprint Retrospective

**¿Qué salió bien?**
- Configuración rápida del entorno
- Sanctum funcionó sin problemas

**¿Qué mejorar?**
- Documentar mejor las decisiones técnicas
- Hacer commits más frecuentes

**Acciones:**
- Crear README con instrucciones
- Establecer convención de commits

---

## 🏃 SPRINT 2: GESTIÓN DE FUNCIONES Y MESAS
**Duración:** 7 días
**Objetivo:** Implementar la gestión de funciones y configuración de mesas

### 📊 Sprint Planning

**Story Points Totales:** 21 puntos
**Velocidad Real Sprint 1:** 21 puntos

### 📝 User Stories

#### US-006: CRUD de Funciones (8 pts)
**Como** administrador
**Quiero** gestionar funciones
**Para** publicar eventos en el sistema

**Criterios de Aceptación:**
- ✅ Crear función con todos los datos
- ✅ Editar función existente
- ✅ Eliminar función
- ✅ Listar funciones
- ✅ Subir hasta 3 imágenes

**Tareas:**
- [ ] Crear FuncionController
- [ ] Crear API endpoints
- [ ] Crear componente Vue de gestión
- [ ] Implementar subida de imágenes
- [ ] Validaciones de formulario

---

#### US-007: Configuración de Mesas (5 pts)
**Como** administrador
**Quiero** configurar las mesas del local
**Para** que estén disponibles para reservas

**Criterios de Aceptación:**
- ✅ 23 mesas con 3 sillas cada una
- ✅ 9 taburetes de barra
- ✅ Zonas definidas (escenario, centro, fondo, barra)
- ✅ Numeración correcta

**Tareas:**
- [ ] Crear migración de mesas
- [ ] Crear MesasRealesSeeder
- [ ] Definir estructura de datos
- [ ] Poblar base de datos

---

#### US-008: Vista Pública de Funciones (5 pts)
**Como** cliente
**Quiero** ver las funciones disponibles
**Para** decidir cuál reservar

**Criterios de Aceptación:**
- ✅ Lista de funciones activas
- ✅ Detalles de cada función
- ✅ Imágenes visibles
- ✅ Filtros por fecha/género
- ✅ Diseño atractivo

**Tareas:**
- [ ] Crear componente Funciones.vue
- [ ] Crear componente FuncionDetalle.vue
- [ ] Implementar API de consulta
- [ ] Diseñar cards de funciones
- [ ] Agregar filtros

---

#### US-009: Panel Admin - Dashboard (3 pts)
**Como** administrador
**Quiero** ver un resumen del sistema
**Para** tener visión general del negocio

**Criterios de Aceptación:**
- ✅ Resumen de reservas del día
- ✅ Próximas funciones
- ✅ Estadísticas básicas
- ✅ Acceso rápido a módulos

**Tareas:**
- [ ] Crear Dashboard.vue
- [ ] Crear API de estadísticas
- [ ] Diseñar widgets
- [ ] Implementar gráficos básicos

---

### 📈 Sprint Review

**Entregables:**
- ✅ Gestión completa de funciones
- ✅ Mesas configuradas en BD
- ✅ Vista pública de funciones
- ✅ Dashboard administrativo

**Demo:**
- Crear una función con imágenes
- Ver funciones en página pública
- Dashboard con estadísticas

---

### 🔄 Sprint Retrospective

**¿Qué salió bien?**
- CRUD de funciones muy completo
- Seeder de mesas bien estructurado

**¿Qué mejorar?**
- Optimizar carga de imágenes
- Mejorar validaciones

**Acciones:**
- Implementar lazy loading de imágenes
- Crear validaciones reutilizables

---

## 🏃 SPRINT 3: SISTEMA DE RESERVAS Y MAPA INTERACTIVO
**Duración:** 7 días
**Objetivo:** Implementar el core del sistema - reservas con mapa interactivo

### 📊 Sprint Planning

**Story Points Totales:** 34 puntos (Sprint más complejo)
**Velocidad Ajustada:** 34 puntos

### 📝 User Stories

#### US-010: Mapa Interactivo del Local (13 pts)
**Como** cliente
**Quiero** ver un mapa visual del local
**Para** elegir exactamente dónde sentarme

**Criterios de Aceptación:**
- ✅ Croquis visual con camerino, escenario, barra
- ✅ 23 mesas redondas dibujadas
- ✅ 9 taburetes de barra en forma de L
- ✅ Entrada/salida visible
- ✅ Indicadores de disponibilidad (verde/naranja/rojo)
- ✅ Clickeable para seleccionar
- ✅ Responsive (móvil, tablet, desktop)

**Tareas:**
- [ ] Diseñar layout del croquis
- [ ] Crear componente Reservar.vue
- [ ] Dibujar elementos con CSS
- [ ] Implementar lógica de colores
- [ ] Hacer responsive
- [ ] Agregar animaciones

---

#### US-011: Reserva de Sillas Individuales (8 pts)
**Como** cliente
**Quiero** reservar sillas individuales
**Para** no tener que reservar toda la mesa

**Criterios de Aceptación:**
- ✅ Seleccionar 1, 2 o 3 sillas por mesa
- ✅ Opción "Mesa completa"
- ✅ Ver sillas ocupadas/disponibles
- ✅ Validar disponibilidad en tiempo real
- ✅ Guardar sillas_reservadas en JSON

**Tareas:**
- [ ] Crear modal de selección
- [ ] Implementar checkboxes de sillas
- [ ] Lógica de validación
- [ ] Actualizar modelo Reserva
- [ ] API de disponibilidad

---

#### US-012: Sistema de Precios Flexible (5 pts)
**Como** cliente
**Quiero** elegir tipo de entrada y extras
**Para** personalizar mi reserva

**Criterios de Aceptación:**
- ✅ Seleccionar Normal (Bs. 40) o VIP (Bs. 50)
- ✅ Opción de agregar cena (+Bs. 30)
- ✅ Cálculo automático del total
- ✅ Desglose visible del precio
- ✅ Precio por persona

**Tareas:**
- [ ] Crear selector de tipo entrada
- [ ] Implementar checkbox de cena
- [ ] Función de cálculo de precio
- [ ] Mostrar desglose
- [ ] Validar montos

---

#### US-013: Confirmación de Reserva (5 pts)
**Como** cliente
**Quiero** confirmar mi reserva
**Para** asegurar mi lugar

**Criterios de Aceptación:**
- ✅ Revisar todos los detalles
- ✅ Generar código único de reserva
- ✅ Guardar en base de datos
- ✅ Mostrar confirmación
- ✅ Redirigir a "Mis Reservas"

**Tareas:**
- [ ] Crear ReservaController
- [ ] Implementar lógica de guardado
- [ ] Generar código único
- [ ] Validar disponibilidad final
- [ ] Crear vista de confirmación

---

#### US-014: Mis Reservas (3 pts)
**Como** cliente
**Quiero** ver mis reservas
**Para** gestionar mis asistencias

**Criterios de Aceptación:**
- ✅ Lista de todas mis reservas
- ✅ Ver detalles completos
- ✅ Cancelar reserva (si está confirmada)
- ✅ Ver código de reserva
- ✅ Estados visibles

**Tareas:**
- [ ] Crear MisReservas.vue
- [ ] API de consulta de reservas
- [ ] Implementar cancelación
- [ ] Diseñar cards de reservas

---

### 📈 Sprint Review

**Entregables:**
- ✅ Mapa interactivo completo
- ✅ Sistema de reservas funcional
- ✅ Reservas de sillas individuales
- ✅ Cálculo de precios automático
- ✅ Gestión de reservas del cliente

**Demo:**
- Reservar desde el mapa interactivo
- Seleccionar sillas específicas
- Ver precio calculado
- Confirmar y ver en "Mis Reservas"

---

### 🔄 Sprint Retrospective

**¿Qué salió bien?**
- Mapa interactivo quedó muy visual
- Lógica de sillas individuales funciona perfecto

**¿Qué mejorar?**
- Optimizar consultas de disponibilidad
- Mejorar UX del modal de reserva

**Acciones:**
- Implementar caché de disponibilidad
- Agregar más feedback visual

---

## 🏃 SPRINT 4: PÁGINA PÚBLICA Y PULIDO FINAL
**Duración:** 7 días
**Objetivo:** Completar página pública, responsive y pulir detalles

### 📊 Sprint Planning

**Story Points Totales:** 21 puntos
**Velocidad Promedio:** 25 puntos/sprint

### 📝 User Stories

#### US-015: Página de Inicio Profesional (8 pts)
**Como** visitante
**Quiero** ver una página de inicio atractiva
**Para** conocer MERAKI y sus servicios

**Criterios de Aceptación:**
- ✅ Hero section con gradiente
- ✅ Sección de próximas funciones (3 últimas)
- ✅ Preview del mapa interactivo
- ✅ Sección de menú con descarga PDF
- ✅ Tabla de precios
- ✅ Información de contacto
- ✅ Footer completo

**Tareas:**
- [ ] Diseñar Home.vue
- [ ] Crear secciones
- [ ] Integrar con API de funciones
- [ ] Agregar animaciones
- [ ] Optimizar imágenes

---

#### US-016: Gestión de Reservas (Admin) (5 pts)
**Como** administrador
**Quiero** gestionar todas las reservas
**Para** controlar el negocio

**Criterios de Aceptación:**
- ✅ Ver todas las reservas
- ✅ Filtrar por fecha/función/estado
- ✅ Confirmar reservas
- ✅ Cancelar reservas
- ✅ Ver detalles completos

**Tareas:**
- [ ] Crear AdminReservas.vue
- [ ] Implementar filtros
- [ ] API de gestión
- [ ] Acciones de confirmación/cancelación

---

#### US-017: Responsive Design Completo (5 pts)
**Como** usuario
**Quiero** usar el sistema desde cualquier dispositivo
**Para** tener flexibilidad

**Criterios de Aceptación:**
- ✅ Funciona en móviles (320px+)
- ✅ Funciona en tablets (768px+)
- ✅ Funciona en desktop (1024px+)
- ✅ Mapa adaptable
- ✅ Navegación táctil optimizada

**Tareas:**
- [ ] Media queries para todas las vistas
- [ ] Optimizar mapa para móvil
- [ ] Ajustar tamaños de elementos
- [ ] Probar en dispositivos reales

---

#### US-018: Pulido y Testing (3 pts)
**Como** equipo
**Queremos** un sistema sin errores
**Para** entregar calidad

**Criterios de Aceptación:**
- ✅ Sin errores de consola
- ✅ Validaciones completas
- ✅ Mensajes de error claros
- ✅ Loading states
- ✅ Manejo de errores

**Tareas:**
- [ ] Testing manual completo
- [ ] Corregir bugs encontrados
- [ ] Mejorar mensajes
- [ ] Agregar loaders
- [ ] Optimizar rendimiento

---

### 📈 Sprint Review

**Entregables:**
- ✅ Página de inicio completa
- ✅ Panel admin de reservas
- ✅ Sistema 100% responsive
- ✅ Sistema pulido y sin errores

**Demo:**
- Tour completo del sistema
- Demostración en móvil
- Proceso completo de reserva
- Panel administrativo

---

### 🔄 Sprint Retrospective Final

**¿Qué salió bien?**
- Cumplimos todos los sprints a tiempo
- Sistema completo y funcional
- Diseño profesional logrado

**¿Qué mejorar?**
- Documentación técnica
- Tests automatizados

**Lecciones Aprendidas:**
- SCRUM funcionó muy bien para este proyecto
- Sprints de 1 semana son ideales
- Priorización correcta fue clave

---

## 📊 MÉTRICAS DEL PROYECTO

### Velocity Chart

```
Sprint 1: 21 puntos ████████████████████
Sprint 2: 21 puntos ████████████████████
Sprint 3: 34 puntos ████████████████████████████████
Sprint 4: 21 puntos ████████████████████

Promedio: 24.25 puntos/sprint
```

### Burndown Chart (Sprint 3 - Ejemplo)

```
Puntos
34 │●
30 │ ●
25 │  ●
20 │   ●
15 │    ●
10 │     ●
5  │      ●
0  │_______●___
   1 2 3 4 5 6 7  Días
```

### Distribución de Story Points

```
Autenticación:     13 pts (13%)
Funciones:         16 pts (17%)
Reservas:          34 pts (35%)
Página Pública:    13 pts (13%)
Admin:             8 pts  (8%)
Responsive:        5 pts  (5%)
Otros:             8 pts  (8%)
```

---

## 🎯 DEFINITION OF DONE (DoD)

Una User Story se considera DONE cuando:

✅ **Código:**
- Código escrito y funcional
- Sin errores de consola
- Código limpio y comentado

✅ **Testing:**
- Probado manualmente
- Funciona en todos los navegadores
- Funciona en móvil

✅ **Documentación:**
- README actualizado
- Comentarios en código complejo

✅ **Integración:**
- Merged a rama principal
- Sin conflictos
- Deploy funcional

✅ **Aceptación:**
- Cumple criterios de aceptación
- Aprobado por Product Owner
- Demo exitosa

---

## 📅 CEREMONIAS SCRUM

### Daily Standup (Diario - 15 min)
**Preguntas:**
1. ¿Qué hice ayer?
2. ¿Qué haré hoy?
3. ¿Tengo algún impedimento?

### Sprint Planning (Inicio de Sprint - 2 horas)
**Agenda:**
1. Revisar Product Backlog
2. Seleccionar User Stories
3. Estimar Story Points
4. Definir Sprint Goal
5. Crear Sprint Backlog

### Sprint Review (Fin de Sprint - 1 hora)
**Agenda:**
1. Demo del incremento
2. Feedback del Product Owner
3. Actualizar Product Backlog

### Sprint Retrospective (Fin de Sprint - 1 hora)
**Agenda:**
1. ¿Qué salió bien?
2. ¿Qué mejorar?
3. Acciones de mejora

---

## 🏆 ROLES SCRUM

### Product Owner
**Responsabilidades:**
- Definir visión del producto
- Priorizar Product Backlog
- Aceptar o rechazar trabajo
- Representar al cliente (dueño de MERAKI)

### Scrum Master
**Responsabilidades:**
- Facilitar ceremonias
- Remover impedimentos
- Proteger al equipo
- Promover SCRUM

### Development Team
**Responsabilidades:**
- Desarrollar el producto
- Auto-organizarse
- Cumplir compromisos
- Mejorar continuamente

---

## 📈 PRODUCT BACKLOG REFINEMENT

### Técnicas de Estimación

**Planning Poker:**
- Fibonacci: 1, 2, 3, 5, 8, 13, 21
- Consenso del equipo
- Re-estimación si es necesario

**Criterios de Estimación:**
- Complejidad técnica
- Esfuerzo requerido
- Incertidumbre
- Dependencias

---

## 🚀 RELEASE PLAN

### Release 1.0 (Fin Sprint 4)
**Fecha:** Semana 4
**Contenido:**
- Sistema completo funcional
- Todas las features principales
- Responsive
- Documentación

### Release 1.1 (Fase 2 - Futuro)
**Fecha:** +1-3 meses
**Contenido:**
- Pagos online
- Notificaciones
- Códigos QR
- Reportes avanzados

---

*Documento de Metodología SCRUM para Sistema MERAKI*
*Versión: 1.0*
