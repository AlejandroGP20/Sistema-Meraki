# 💰 SISTEMA DE PRECIOS - MERAKI Teatro Bar

## 📋 Precios Base (POR PERSONA)

### Mesa Estándar
- **40 Bs por persona** - Entrada general

### Mesa VIP
- **50 Bs por persona** - Entrada VIP (incluye trago de cortesía)
  - Equivale a: 40 Bs (entrada) + 10 Bs (trago)

### Cena (Opcional)
- **+30 Bs por persona** - Se agrega a cualquier tipo de entrada

---

## 🧮 EJEMPLOS DE CÁLCULO

### Ejemplo 1: Mesa Estándar - 2 Personas - Sin Cena
```
Entrada: 40 Bs x 2 personas = 80 Bs
─────────────────────────────────
TOTAL: 80 Bs
```

### Ejemplo 2: Mesa Estándar - 4 Personas - Con Cena
```
Entrada: 40 Bs x 4 personas = 160 Bs
Cena: 30 Bs x 4 personas = 120 Bs
─────────────────────────────────
TOTAL: 280 Bs
```

### Ejemplo 3: Mesa VIP - 2 Personas - Sin Cena
```
Entrada VIP (incluye trago): 50 Bs x 2 personas = 100 Bs
─────────────────────────────────
TOTAL: 100 Bs
```

### Ejemplo 4: Mesa VIP - 4 Personas - Con Cena
```
Entrada VIP (incluye trago): 50 Bs x 4 personas = 200 Bs
Cena: 30 Bs x 4 personas = 120 Bs
─────────────────────────────────
TOTAL: 320 Bs
```

### Ejemplo 5: Mesa VIP Grande - 6 Personas - Con Cena
```
Entrada VIP (incluye trago): 50 Bs x 6 personas = 300 Bs
Cena: 30 Bs x 6 personas = 180 Bs
─────────────────────────────────
TOTAL: 480 Bs
```

---

## 🗺️ MESAS Y CAPACIDADES

### Zona Escenario (Mesas 1-8) - ⭕ Círculos

| Mesa | Capacidad | Tipo | Sin Cena | Con Cena |
|------|-----------|------|----------|----------|
| 1 | 2 personas | Estándar | 80 Bs | 140 Bs |
| 2 | 4 personas | **VIP** | 200 Bs | 320 Bs |
| 3 | 2 personas | Estándar | 80 Bs | 140 Bs |
| 4 | 4 personas | **VIP** | 200 Bs | 320 Bs |
| 5 | 2 personas | Estándar | 80 Bs | 140 Bs |
| 6 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 7 | 2 personas | Estándar | 80 Bs | 140 Bs |
| 8 | 4 personas | **VIP** | 200 Bs | 320 Bs |

**Cálculo Mesa VIP 4 personas:**
- Sin cena: 50 × 4 = 200 Bs
- Con cena: (50 + 30) × 4 = 320 Bs

### Zona Barra (Mesas 9-16) - ⬜ Cuadrados

| Mesa | Capacidad | Tipo | Sin Cena | Con Cena |
|------|-----------|------|----------|----------|
| 9-16 | 2 personas | Barra | 80 Bs | 140 Bs |

**Todas las mesas de barra son estándar para 2 personas**

### Zona Plaza (Mesas 17-25) - ⬡ Hexágonos

| Mesa | Capacidad | Tipo | Sin Cena | Con Cena |
|------|-----------|------|----------|----------|
| 17 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 18 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 19 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 20 | 6 personas | **VIP** | 300 Bs | 480 Bs |
| 21 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 22 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 23 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 24 | 4 personas | Estándar | 160 Bs | 280 Bs |
| 25 | 6 personas | **VIP** | 300 Bs | 480 Bs |

**Cálculo Mesa VIP 6 personas:**
- Sin cena: 50 × 6 = 300 Bs
- Con cena: (50 + 30) × 6 = 480 Bs

---

## 💡 NOTAS IMPORTANTES

### Diferencia entre Mesa Estándar y VIP

#### Mesa Estándar (40 Bs/persona):
- ✅ Acceso al show
- ✅ Mesa asignada

#### Mesa VIP (50 Bs/persona):
- ✅ Acceso al show
- ✅ Mesa asignada en zona preferencial
- ✅ **1 Trago de cortesía por persona** 🍹
- ⭐ Servicio prioritario

**Diferencia de precio: Solo 10 Bs más por persona**

### Cena (Opcional)
- Agrega **30 Bs por persona** a cualquier tipo de entrada
- Disponible tanto para mesas estándar como VIP
- Incluye plato completo

---

## 🎯 CÓMO SE CALCULA EN EL SISTEMA

### Fórmula:
```
TOTAL = Precio_Base × Num_Personas + (Cena × Num_Personas)

Donde:
- Precio_Base = 40 Bs (mesa estándar) o 50 Bs (mesa VIP)
- Cena = 30 Bs (si se selecciona), 0 Bs (si no)
- Num_Personas = Cantidad de personas en la reserva
```

### Ejemplo en Código:
```javascript
// Frontend (Vue)
const calcularTotal = () => {
  const numPersonas = parseInt(reservaForm.value.num_personas);
  
  // Precio base según tipo de mesa
  let precioPorPersona = selectedMesa.value.es_vip ? 50 : 40;
  
  // Agregar cena si se selecciona
  if (reservaForm.value.incluye_cena) {
    precioPorPersona += 30;
  }
  
  return precioPorPersona * numPersonas;
};
```

```php
// Backend (Laravel)
// Precio base según tipo de mesa
if ($mesa->es_vip) {
    $precioPorPersona = $funcion->precio_vip;  // 50 Bs
} else {
    $precioPorPersona = $funcion->precio_entrada;  // 40 Bs
}

// Agregar cena si se selecciona
if ($request->incluye_cena) {
    $precioPorPersona += 30;
}

$monto = $precioPorPersona * $request->num_personas;
```

---

## 📊 TABLA RESUMEN DE PRECIOS

### Por Persona:

| Tipo de Mesa | Sin Cena | Con Cena | Diferencia |
|--------------|----------|----------|------------|
| **Estándar** | 40 Bs | 70 Bs | +30 Bs |
| **VIP** | 50 Bs | 80 Bs | +30 Bs |

### Ejemplos por Grupo:

| Personas | Mesa Estándar (sin cena) | Mesa VIP (sin cena) | Diferencia |
|----------|-------------------------|---------------------|------------|
| 2 | 80 Bs | 100 Bs | +20 Bs |
| 4 | 160 Bs | 200 Bs | +40 Bs |
| 6 | 240 Bs | 300 Bs | +60 Bs |

| Personas | Mesa Estándar (con cena) | Mesa VIP (con cena) | Diferencia |
|----------|-------------------------|---------------------|------------|
| 2 | 140 Bs | 160 Bs | +20 Bs |
| 4 | 280 Bs | 320 Bs | +40 Bs |
| 6 | 420 Bs | 480 Bs | +60 Bs |

---

## 🎁 BENEFICIOS MESA VIP

Por solo **10 Bs adicionales por persona**, cada cliente recibe:

- 🍹 **1 Trago de cortesía** (cóctel de la casa o bebida premium)
- 💺 **Ubicación preferencial** (mejores vistas del escenario)
- ⭐ **Servicio prioritario**
- 💜 **Identificación especial** en el sistema

**¡Vale la pena el upgrade!**

---

## 📈 RANGOS DE PRECIO

### Mínimo:
- **80 Bs** - 2 personas, mesa estándar, sin cena

### Promedio:
- **160-200 Bs** - 4 personas, sin cena
- **280-320 Bs** - 4 personas, con cena

### Máximo:
- **480 Bs** - 6 personas, mesa VIP, con cena

---

¡Sistema de precios configurado correctamente! 💰✨

**Resumen:**
- Mesa Estándar: 40 Bs/persona
- Mesa VIP: 50 Bs/persona (incluye trago)
- Cena: +30 Bs/persona (opcional)
