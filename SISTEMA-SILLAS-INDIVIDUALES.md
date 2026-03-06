# 🪑 SISTEMA DE RESERVAS POR SILLA - MERAKI

## 📊 NUEVA ESTRUCTURA

### Mesas y Capacidades:
- **23 mesas** con 3 sillas cada una = 69 sillas
- **9 taburetes** en la barra = 9 asientos individuales
- **Total**: 78 asientos disponibles

---

## 🎯 TIPOS DE RESERVA

### 1. Reserva de Silla Individual
- El cliente selecciona sillas específicas (ej: Silla 1 y Silla 2 de la Mesa 5)
- Precio: Por persona
- Otras personas pueden reservar las sillas restantes de la misma mesa

### 2. Reserva de Mesa Completa
- El cliente reserva las 3 sillas de la mesa
- Precio: Por persona × 3
- Nadie más puede reservar esa mesa

### 3. Reserva de Taburete
- Asiento individual en la barra
- Precio: Por persona
- No se puede reservar "barra completa"

---

## 🗺️ LAYOUT DEL LOCAL

```
┌─────────────────────────────────────────────────────────────┐
│  CAMERINO          ESCENARIO                    BARRA       │
│                                                 🪑🪑🪑🪑🪑🪑  │
│                                                 🪑🪑🪑       │
├─────────────────────────────────────────────────────────────┤
│                                                        🚪   │
│  FILA 1 (Frente al escenario)                      ENTRADA │
│  ⭕1  ⭕2  ⭕3  ⭕4  ⭕5  ⭕6                         SALIDA  │
│                                                     ➡️     │
│  FILA 2 (Centro)                                           │
│  ⭕7  ⭕8  ⭕9  ⭕10 ⭕11 ⭕12 ⭕13 ⭕14                        │
│                                                             │
│  FILA 3 (Fondo)                                            │
│  ⭕15 ⭕16 ⭕17 ⭕18 ⭕19 ⭕20 ⭕21 ⭕22 ⭕23                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 💾 ESTRUCTURA DE DATOS

### Tabla `mesas`:
```sql
id: 1
numero: 1
zona: 'escenario'
capacidad: 3
tipo: 'mesa'
```

### Tabla `reservas`:
```sql
id: 1
mesa_id: 5
sillas_reservadas: [1, 2]  -- JSON array
mesa_completa: false
num_personas: 2
```

---

## 🎨 INTERFAZ DE USUARIO

### Vista de Mapa:
```
Mesa 5 [⭕]
├─ Silla 1: 🟢 Disponible
├─ Silla 2: 🟢 Disponible  
└─ Silla 3: 🔴 Reservada
```

### Modal de Selección:
```
┌─────────────────────────────────────┐
│  Mesa 5 - Zona Escenario            │
├─────────────────────────────────────┤
│  Selecciona tus asientos:           │
│                                     │
│  ☐ Silla 1  ☐ Silla 2  ☑ Silla 3  │
│                                     │
│  O reserva la mesa completa:        │
│  ☐ Mesa Completa (3 sillas)        │
│                                     │
│  Tipo de Entrada:                   │
│  ⦿ Normal - 40 Bs/persona           │
│  ○ VIP - 50 Bs/persona              │
│                                     │
│  ☐ Incluir cena (+30 Bs/persona)   │
│                                     │
│  Total: 2 personas × 40 Bs = 80 Bs │
│                                     │
│  [Confirmar]  [Cancelar]            │
└─────────────────────────────────────┘
```

---

## 🔄 FLUJO DE RESERVA

### Paso 1: Cliente selecciona mesa
- Click en mesa del mapa
- Se abre modal mostrando sillas disponibles

### Paso 2: Cliente elige sillas
- Opción A: Selecciona sillas individuales (1, 2, o 3)
- Opción B: Marca "Mesa Completa"

### Paso 3: Cliente configura reserva
- Tipo de entrada (Normal/VIP)
- Cena (Sí/No)
- Ve el total calculado

### Paso 4: Confirmación
- Sistema verifica disponibilidad
- Guarda reserva con sillas específicas
- Genera código de reserva

---

## 🎯 REGLAS DE NEGOCIO

### Disponibilidad:
- ✅ Puedo reservar Silla 1 si está libre
- ✅ Puedo reservar Silla 1 y 2 juntas
- ❌ No puedo reservar Silla 1 si ya está reservada
- ❌ No puedo reservar mesa completa si alguna silla está ocupada

### Precios:
- Precio por silla = Precio por persona
- Mesa completa = Precio por persona × 3
- VIP y cena se aplican por persona

### Cancelación:
- Al cancelar, se liberan solo las sillas reservadas
- Otras personas pueden reservar las sillas liberadas

---

## 📊 EJEMPLOS

### Ejemplo 1: Pareja reserva 2 sillas
```
Mesa: 10
Sillas: [1, 2]
Personas: 2
Tipo: Normal
Cena: No
Total: 40 × 2 = 80 Bs
```

### Ejemplo 2: Grupo reserva mesa completa
```
Mesa: 15
Mesa Completa: Sí
Personas: 3
Tipo: VIP
Cena: Sí
Total: (50 + 30) × 3 = 240 Bs
```

### Ejemplo 3: Persona sola en taburete
```
Taburete: 24
Personas: 1
Tipo: Normal
Cena: No
Total: 40 × 1 = 40 Bs
```

---

## 🚀 PRÓXIMOS PASOS

1. ✅ Base de datos actualizada
2. ✅ Mesas reales cargadas (23 mesas + 9 taburetes)
3. ⏳ Componente Vue con layout visual
4. ⏳ Selector de sillas interactivo
5. ⏳ Lógica de disponibilidad por silla
6. ⏳ API actualizada para soportar sillas

---

¿Quieres que continúe con la implementación del componente Vue visual?
