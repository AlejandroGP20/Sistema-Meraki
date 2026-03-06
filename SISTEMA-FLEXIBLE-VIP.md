# 🎯 SISTEMA FLEXIBLE DE RESERVAS - MERAKI

## ✨ NUEVA FUNCIONALIDAD

**TODAS las 25 mesas pueden reservarse como Normal o VIP según la preferencia del cliente**

Ya no hay mesas pre-asignadas como VIP. El cliente elige al momento de reservar.

---

## 🎫 OPCIONES DE ENTRADA

### Opción 1: Entrada Normal
- **40 Bs por persona**
- Incluye: Acceso al show
- Disponible en cualquier mesa

### Opción 2: Entrada VIP
- **50 Bs por persona**
- Incluye: Acceso al show + Trago de cortesía 🍹
- Disponible en cualquier mesa
- Solo 10 Bs más que la entrada normal

### Opción 3: Agregar Cena (Opcional)
- **+30 Bs por persona**
- Se puede agregar a cualquier tipo de entrada
- Disponible tanto para Normal como VIP

---

## 💰 TABLA DE PRECIOS

| Tipo de Entrada | Sin Cena | Con Cena |
|-----------------|----------|----------|
| **Normal** | 40 Bs/persona | 70 Bs/persona |
| **VIP** | 50 Bs/persona | 80 Bs/persona |

---

## 🎨 FLUJO DE RESERVA

### Paso 1: Seleccionar Mesa
- El cliente ve las 25 mesas disponibles
- Todas las mesas disponibles aparecen en verde
- Las mesas reservadas aparecen en rojo (no seleccionables)

### Paso 2: Elegir Tipo de Entrada
Al hacer click en una mesa, aparece un modal con:

```
┌─────────────────────────────────────┐
│  Confirmar Reserva                  │
├─────────────────────────────────────┤
│  Mesa: 5                            │
│  Zona: escenario                    │
│  Capacidad: 4 personas              │
│                                     │
│  Número de personas: [2]            │
│                                     │
│  Tipo de Entrada:                   │
│  ○ Normal - Bs. 40/persona          │
│     Entrada al show                 │
│                                     │
│  ○ ⭐ VIP - Bs. 50/persona          │
│     Entrada + Trago de cortesía     │
│                                     │
│  ☐ Incluir cena (+Bs. 30/persona)  │
│                                     │
│  Desglose de Precio:                │
│  Entrada Normal: Bs. 40 x 2 = 80 Bs│
│                                     │
│  Total a Pagar: Bs. 80              │
│                                     │
│  [Confirmar Reserva]  [Cancelar]    │
└─────────────────────────────────────┘
```

### Paso 3: Confirmar
- El sistema calcula automáticamente el total
- Se guarda la reserva con el tipo elegido
- El cliente recibe su código de reserva

---

## 📊 EJEMPLOS DE RESERVAS

### Ejemplo 1: Grupo de 2 - Entrada Normal
```
Mesa: 10 (Barra)
Personas: 2
Tipo: Normal (40 Bs/persona)
Cena: No

Cálculo:
40 × 2 = 80 Bs
```

### Ejemplo 2: Grupo de 4 - Entrada VIP con Cena
```
Mesa: 20 (Plaza)
Personas: 4
Tipo: VIP (50 Bs/persona)
Cena: Sí (+30 Bs/persona)

Cálculo:
(50 + 30) × 4 = 320 Bs
```

### Ejemplo 3: Grupo de 6 - Entrada Normal con Cena
```
Mesa: 25 (Plaza)
Personas: 6
Tipo: Normal (40 Bs/persona)
Cena: Sí (+30 Bs/persona)

Cálculo:
(40 + 30) × 6 = 420 Bs
```

### Ejemplo 4: Pareja - Entrada VIP sin Cena
```
Mesa: 1 (Escenario)
Personas: 2
Tipo: VIP (50 Bs/persona)
Cena: No

Cálculo:
50 × 2 = 100 Bs
```

---

## 🎁 BENEFICIOS DEL SISTEMA FLEXIBLE

### Para el Cliente:
- ✅ **Libertad de elección**: Cualquier mesa puede ser VIP
- ✅ **Mejor ubicación**: Puede elegir VIP en la zona que prefiera
- ✅ **Precio justo**: Solo paga VIP si lo desea
- ✅ **Transparencia**: Ve el desglose completo antes de confirmar

### Para el Negocio:
- ✅ **Mayor flexibilidad**: No hay mesas "bloqueadas" como VIP
- ✅ **Mejor ocupación**: Todas las mesas se pueden vender
- ✅ **Upselling fácil**: El cliente ve la opción VIP al reservar
- ✅ **Gestión simple**: No hay que pre-asignar mesas VIP

---

## 🔧 IMPLEMENTACIÓN TÉCNICA

### Frontend (Vue)
```javascript
// El usuario elige el tipo de entrada
reservaForm.value = {
  num_personas: 2,
  es_vip: false,  // o true según elección
  incluye_cena: false
};

// Cálculo dinámico
const precioPorPersona = reservaForm.value.es_vip ? 50 : 40;
if (reservaForm.value.incluye_cena) {
  precioPorPersona += 30;
}
const total = precioPorPersona * reservaForm.value.num_personas;
```

### Backend (Laravel)
```php
// Se usa el valor es_vip del request, no de la mesa
$precioPorPersona = $request->es_vip ? 50 : 40;

if ($request->incluye_cena) {
    $precioPorPersona += 30;
}

$monto = $precioPorPersona * $request->num_personas;
```

### Base de Datos
```sql
-- La tabla reservas guarda la elección del cliente
reservas:
  - es_vip: boolean (elección del cliente)
  - incluye_cena: boolean
  - num_personas: integer
  - monto_total: decimal

-- La tabla mesas ya no necesita el campo es_vip
-- (aunque se mantiene por compatibilidad)
```

---

## 📈 ESTADÍSTICAS POSIBLES

Con este sistema puedes analizar:
- ¿Qué porcentaje de clientes elige VIP?
- ¿En qué zonas se elige más VIP?
- ¿Cuántos clientes agregan cena?
- Ingreso promedio por reserva
- Conversión de Normal a VIP

---

## 🎯 VENTAJAS COMPETITIVAS

1. **Flexibilidad Total**: El cliente no está limitado por mesas pre-asignadas
2. **Experiencia Premium Accesible**: Solo 10 Bs más para upgrade a VIP
3. **Transparencia**: El cliente ve exactamente qué incluye cada opción
4. **Upselling Natural**: La opción VIP siempre está visible
5. **Gestión Simplificada**: No hay que decidir qué mesas son VIP

---

## ✅ CHECKLIST DE PRUEBA

- [ ] Seleccionar cualquier mesa disponible
- [ ] Ver las dos opciones: Normal y VIP
- [ ] Cambiar entre Normal y VIP
- [ ] Ver el precio actualizarse en tiempo real
- [ ] Agregar/quitar cena
- [ ] Ver el desglose completo
- [ ] Confirmar reserva
- [ ] Verificar que se guardó correctamente el tipo elegido

---

¡Sistema flexible implementado y funcionando! 🎉

**Recuerda:** Ahora TODAS las mesas son iguales. El cliente decide si quiere experiencia Normal o VIP al momento de reservar.
