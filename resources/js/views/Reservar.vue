<template>
  <div class="reservar-page">
    <nav class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <img src="/images/MerakiLogo.jpg" alt="MERAKI Logo" class="navbar-logo" />
          <router-link to="/" class="logo">MERAKI</router-link>
        </div>
        <div class="nav-links">
          <router-link to="/">Inicio</router-link>
          <router-link to="/funciones">Funciones</router-link>
        </div>
      </div>
    </nav>

    <div class="container">
      <h1>Selecciona tu Mesa o Silla</h1>
      
      <div v-if="funcion" class="funcion-info">
        <h2>{{ funcion.nombre }}</h2>
        <p>{{ formatDate(funcion.fecha) }} - {{ funcion.horario }}</p>
      </div>

      <div class="leyenda">
        <div class="leyenda-items">
          <div class="leyenda-item">
            <span class="color-box disponible"></span> Disponible
          </div>
          <div class="leyenda-item">
            <span class="color-box parcial"></span> Parcialmente ocupada
          </div>
          <div class="leyenda-item">
            <span class="color-box reservada"></span> Reservada
          </div>
        </div>
        <p class="leyenda-note">💡 Puedes reservar sillas individuales o la mesa completa. Todas pueden ser Normal (40 Bs) o VIP (50 Bs + trago)</p>
      </div>

      <!-- Croquis del local -->
      <div class="croquis-container">
        <div class="local-layout">
          <!-- Fila superior: Camerino, Escenario, Barra -->
          <div class="fila-superior">
            <!-- Camerino -->
            <div class="camerino">
              <div class="camerino-label">CAMERINO</div>
            </div>

            <!-- Escenario -->
            <div class="escenario">
              <div class="escenario-content">
                <div class="escenario-label">ESCENARIO</div>
                <div class="escenario-lights">
                  <span></span><span></span><span></span><span></span>
                </div>
              </div>
            </div>

            <!-- Barra con taburetes en L -->
            <div class="barra-container">
              <div class="barra-label">BARRA</div>
              <div class="barra-layout-l">
                <!-- 3 taburetes verticales -->
                <div class="taburetes-columna-vertical">
                  <div 
                    v-for="mesa in mesasBarra.slice(0, 3)" 
                    :key="mesa.id"
                    class="taburete-item"
                    :class="getEstadoClass(mesa)"
                    @click="selectMesa(mesa)"
                  >
                    <div class="taburete-visual">
                      <div class="taburete-top"></div>
                      <div class="taburete-legs">
                        <span></span><span></span><span></span>
                      </div>
                    </div>
                    <div class="taburete-num">{{ mesa.numero }}</div>
                  </div>
                </div>
                
                <!-- 6 taburetes horizontales (alineados con el 3er taburete) -->
                <div class="taburetes-fila-horizontal">
                  <div 
                    v-for="mesa in mesasBarra.slice(3, 9)" 
                    :key="mesa.id"
                    class="taburete-item"
                    :class="getEstadoClass(mesa)"
                    @click="selectMesa(mesa)"
                  >
                    <div class="taburete-visual">
                      <div class="taburete-top"></div>
                      <div class="taburete-legs">
                        <span></span><span></span><span></span>
                      </div>
                    </div>
                    <div class="taburete-num">{{ mesa.numero }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Área principal: Mesas y Entrada -->
          <div class="area-principal">
            <!-- Columna de mesas -->
            <div class="columna-mesas">
              <!-- Fila 1: Mesas 1-6 -->
              <div class="fila-mesas">
                <div 
                  v-for="mesa in mesasFila1" 
                  :key="mesa.id"
                  class="mesa-item"
                  :class="getEstadoClass(mesa)"
                  @click="selectMesa(mesa)"
                >
                  <div class="mesa-visual">
                    <div class="mesa-numero">{{ mesa.numero }}</div>
                    <div class="sillas-visual">
                      <div 
                        v-for="silla in 3" 
                        :key="silla"
                        class="silla-mini"
                        :class="getSillaClass(mesa, silla)"
                      >
                        <div class="silla-back-mini"></div>
                        <div class="silla-seat-mini"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Fila 2: Mesas 7-14 -->
              <div class="fila-mesas">
                <div 
                  v-for="mesa in mesasFila2" 
                  :key="mesa.id"
                  class="mesa-item"
                  :class="getEstadoClass(mesa)"
                  @click="selectMesa(mesa)"
                >
                  <div class="mesa-visual">
                    <div class="mesa-numero">{{ mesa.numero }}</div>
                    <div class="sillas-visual">
                      <div 
                        v-for="silla in 3" 
                        :key="silla"
                        class="silla-mini"
                        :class="getSillaClass(mesa, silla)"
                      >
                        <div class="silla-back-mini"></div>
                        <div class="silla-seat-mini"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Fila 3: Mesas 15-23 -->
              <div class="fila-mesas">
                <div 
                  v-for="mesa in mesasFila3" 
                  :key="mesa.id"
                  class="mesa-item"
                  :class="getEstadoClass(mesa)"
                  @click="selectMesa(mesa)"
                >
                  <div class="mesa-visual">
                    <div class="mesa-numero">{{ mesa.numero }}</div>
                    <div class="sillas-visual">
                      <div 
                        v-for="silla in 3" 
                        :key="silla"
                        class="silla-mini"
                        :class="getSillaClass(mesa, silla)"
                      >
                        <div class="silla-back-mini"></div>
                        <div class="silla-seat-mini"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Entrada/Salida a la derecha -->
            <div class="entrada-salida">
              <div class="puerta-visual">
                <div class="puerta-marco">
                  <div class="puerta-hoja"></div>
                </div>
              </div>
              <div class="entrada-texto">ENTRADA<br/>SALIDA</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de selección de sillas -->
      <div v-if="selectedMesa" class="modal" @click.self="cerrarModal">
        <div class="modal-content">
          <h3>{{ selectedMesa.tipo === 'taburete' ? 'Reservar Taburete' : 'Selecciona tus Sillas' }}</h3>
          <p><strong>{{ selectedMesa.tipo === 'taburete' ? 'Taburete' : 'Mesa' }}:</strong> {{ selectedMesa.numero }}</p>
          <p><strong>Zona:</strong> {{ selectedMesa.zona }}</p>
          
          <!-- Selector de sillas para mesas -->
          <div v-if="selectedMesa.tipo === 'mesa'" class="sillas-selector">
            <p><strong>Selecciona las sillas que deseas reservar:</strong></p>
            <div class="sillas-grid">
              <label 
                v-for="silla in selectedMesa.capacidad" 
                :key="silla"
                class="silla-checkbox"
                :class="{
                  'ocupada': selectedMesa.sillas_ocupadas.includes(silla),
                  'seleccionada': reservaForm.sillas_reservadas.includes(silla)
                }"
              >
                <input 
                  type="checkbox" 
                  :value="silla"
                  v-model="reservaForm.sillas_reservadas"
                  :disabled="selectedMesa.sillas_ocupadas.includes(silla)"
                />
                <span class="silla-icon">🪑</span>
                <span class="silla-label">Silla {{ silla }}</span>
                <span v-if="selectedMesa.sillas_ocupadas.includes(silla)" class="ocupada-label">Ocupada</span>
              </label>
            </div>
            
            <div class="mesa-completa-option">
              <label>
                <input 
                  type="checkbox" 
                  v-model="reservaForm.mesa_completa"
                  :disabled="selectedMesa.sillas_ocupadas.length > 0"
                  @change="toggleMesaCompleta"
                />
                <strong>Reservar mesa completa (3 sillas)</strong>
              </label>
            </div>
          </div>
          
          <div v-else class="taburete-info">
            <p>✅ Reservando 1 asiento en la barra</p>
          </div>
          
          <div class="form-group tipo-entrada">
            <label><strong>Tipo de Entrada:</strong></label>
            <div class="radio-group">
              <label class="radio-option">
                <input type="radio" v-model="reservaForm.es_vip" :value="false" />
                <div class="radio-content">
                  <span class="radio-title">Normal - Bs. 40/persona</span>
                  <span class="radio-desc">Entrada al show</span>
                </div>
              </label>
              <label class="radio-option vip-option">
                <input type="radio" v-model="reservaForm.es_vip" :value="true" />
                <div class="radio-content">
                  <span class="radio-title">⭐ VIP - Bs. 50/persona</span>
                  <span class="radio-desc">Entrada + Trago de cortesía</span>
                </div>
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label>
              <input v-model="reservaForm.incluye_cena" type="checkbox" />
              Incluir cena (+Bs. 30 por persona)
            </label>
          </div>
          
          <div class="precio-desglose">
            <p><strong>Desglose de Precio:</strong></p>
            <p>Personas: {{ getNumPersonas() }}</p>
            <p v-if="reservaForm.es_vip">
              Entrada VIP (incluye trago): Bs. 50 x {{ getNumPersonas() }} = 
              Bs. {{ (50 * getNumPersonas()).toFixed(2) }}
            </p>
            <p v-else>
              Entrada Normal: Bs. 40 x {{ getNumPersonas() }} = 
              Bs. {{ (40 * getNumPersonas()).toFixed(2) }}
            </p>
            <p v-if="reservaForm.incluye_cena">
              Cena: Bs. 30 x {{ getNumPersonas() }} = 
              Bs. {{ (30 * getNumPersonas()).toFixed(2) }}
            </p>
          </div>
          
          <p class="total"><strong>Total a Pagar:</strong> Bs. {{ calcularTotal() }}</p>
          
          <div class="modal-actions">
            <button 
              @click="confirmarReserva" 
              class="btn-confirmar"
              :disabled="getNumPersonas() === 0"
            >
              Confirmar Reserva
            </button>
            <button @click="cerrarModal" class="btn-cancelar">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const funcion = ref(null);
const mesas = ref([]);
const selectedMesa = ref(null);

const reservaForm = ref({
  sillas_reservadas: [],
  mesa_completa: false,
  incluye_cena: false,
  es_vip: false,
});

// Computed para separar mesas por zona
const mesasBarra = computed(() => {
  return mesas.value.filter(m => m.tipo === 'taburete').sort((a, b) => a.numero - b.numero);
});

const mesasFila1 = computed(() => {
  return mesas.value.filter(m => m.zona === 'escenario' && m.tipo === 'mesa').sort((a, b) => a.numero - b.numero);
});

const mesasFila2 = computed(() => {
  return mesas.value.filter(m => m.zona === 'centro' && m.tipo === 'mesa').sort((a, b) => a.numero - b.numero);
});

const mesasFila3 = computed(() => {
  return mesas.value.filter(m => m.zona === 'fondo' && m.tipo === 'mesa').sort((a, b) => a.numero - b.numero);
});

const loadData = async () => {
  try {
    const [funcionRes, mesasRes] = await Promise.all([
      axios.get(`/api/funciones/${route.params.funcionId}`),
      axios.get(`/api/mesas/disponibilidad?funcion_id=${route.params.funcionId}`),
    ]);
    
    funcion.value = funcionRes.data;
    mesas.value = mesasRes.data;
    
    console.log('Función cargada:', funcion.value);
    console.log('Mesas cargadas:', mesas.value);
  } catch (error) {
    console.error('Error al cargar datos:', error);
    alert('Error al cargar los datos. Por favor intenta de nuevo.');
  }
};

const getEstadoClass = (mesa) => {
  if (mesa.estado === 'reservada') return 'reservada';
  if (mesa.sillas_ocupadas && mesa.sillas_ocupadas.length > 0 && mesa.sillas_ocupadas.length < mesa.capacidad) {
    return 'parcial';
  }
  return 'disponible';
};

const getSillaClass = (mesa, numeroSilla) => {
  if (mesa.sillas_ocupadas && mesa.sillas_ocupadas.includes(numeroSilla)) {
    return 'ocupada';
  }
  return 'disponible';
};

const selectMesa = (mesa) => {
  if (mesa.estado === 'reservada') {
    alert('Esta mesa/taburete ya está completamente reservada');
    return;
  }
  
  selectedMesa.value = mesa;
  reservaForm.value = {
    sillas_reservadas: [],
    mesa_completa: false,
    incluye_cena: false,
    es_vip: false,
  };
};

const toggleMesaCompleta = () => {
  if (reservaForm.value.mesa_completa) {
    // Si marca mesa completa, seleccionar todas las sillas disponibles
    reservaForm.value.sillas_reservadas = [1, 2, 3];
  } else {
    // Si desmarca, limpiar selección
    reservaForm.value.sillas_reservadas = [];
  }
};

const getNumPersonas = () => {
  if (selectedMesa.value?.tipo === 'taburete') {
    return 1;
  }
  return reservaForm.value.sillas_reservadas.length;
};

const calcularTotal = () => {
  const numPersonas = getNumPersonas();
  if (numPersonas === 0) return '0.00';
  
  let precioPorPersona = reservaForm.value.es_vip ? 50 : 40;
  
  if (reservaForm.value.incluye_cena) {
    precioPorPersona += 30;
  }
  
  const total = precioPorPersona * numPersonas;
  return total.toFixed(2);
};

const cerrarModal = () => {
  selectedMesa.value = null;
  reservaForm.value = {
    sillas_reservadas: [],
    mesa_completa: false,
    incluye_cena: false,
    es_vip: false,
  };
};

const confirmarReserva = async () => {
  try {
    const numPersonas = getNumPersonas();
    
    if (numPersonas === 0) {
      alert('Debes seleccionar al menos una silla');
      return;
    }
    
    const payload = {
      funcion_id: route.params.funcionId,
      mesa_id: selectedMesa.value.id,
      num_personas: numPersonas,
      incluye_cena: reservaForm.value.incluye_cena,
      es_vip: reservaForm.value.es_vip,
    };
    
    // Para taburetes
    if (selectedMesa.value.tipo === 'taburete') {
      payload.sillas_reservadas = [1];
      payload.mesa_completa = false;
    } else {
      // Para mesas
      payload.sillas_reservadas = reservaForm.value.sillas_reservadas;
      payload.mesa_completa = reservaForm.value.mesa_completa;
    }
    
    console.log('Enviando reserva:', payload);
    
    await axios.post('/api/reservas', payload);
    
    alert('¡Reserva confirmada exitosamente!');
    router.push('/mis-reservas');
  } catch (error) {
    console.error('Error al crear reserva:', error);
    console.error('Respuesta del servidor:', error.response?.data);
    alert(error.response?.data?.message || 'Error al crear la reserva');
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-BO', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

onMounted(() => {
  loadData();
});
</script>

<style scoped>
.navbar {
  background: #1a1a1a;
  padding: 1rem 0;
  color: white;
  margin-bottom: 2rem;
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
}

.navbar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.navbar-logo {
  height: 40px;
  width: auto;
  object-fit: contain;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-links a {
  color: white;
  text-decoration: none;
  transition: all 0.3s;
  font-weight: 500;
}

.nav-links a:hover {
  color: #667eea;
  transform: translateY(-2px);
}

h1 {
  text-align: center;
  margin-bottom: 1rem;
}

.funcion-info {
  text-align: center;
  margin-bottom: 2rem;
}

.funcion-info h2 {
  color: #667eea;
  margin-bottom: 0.5rem;
}

.leyenda {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-bottom: 2rem;
}

.leyenda-items {
  display: flex;
  gap: 2rem;
}

.leyenda-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.leyenda-note {
  font-size: 0.9rem;
  color: #667eea;
  font-weight: 500;
  text-align: center;
  background: #f0f4ff;
  padding: 0.5rem 1rem;
  border-radius: 4px;
}

.color-box {
  width: 30px;
  height: 30px;
  border-radius: 4px;
}

.color-box.disponible {
  background: #27ae60;
}

.color-box.parcial {
  background: #f39c12;
}

.color-box.reservada {
  background: #e74c3c;
}

/* Croquis del local - Diseño profesional */
.croquis-container {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-radius: 16px;
  padding: 3rem;
  box-shadow: 0 10px 40px rgba(0,0,0,0.3);
  position: relative;
  overflow: hidden;
}

.croquis-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    repeating-linear-gradient(
      0deg,
      rgba(255,255,255,0.03) 0px,
      transparent 1px,
      transparent 40px,
      rgba(255,255,255,0.03) 41px
    ),
    repeating-linear-gradient(
      90deg,
      rgba(255,255,255,0.03) 0px,
      transparent 1px,
      transparent 40px,
      rgba(255,255,255,0.03) 41px
    );
  pointer-events: none;
}

/* Logo MERAKI - Removido de aquí, ahora está en el navbar */

.local-layout {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  position: relative;
  z-index: 1;
}

/* Fila superior: Camerino, Escenario, Barra */
.fila-superior {
  display: grid;
  grid-template-columns: 200px 1fr 280px;
  gap: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 3px solid rgba(255,255,255,0.15);
}

.taburete-icon {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.taburete-top {
  width: 30px;
  height: 8px;
  background: linear-gradient(135deg, #8b4513 0%, #654321 100%);
  border-radius: 50% 50% 0 0 / 100% 100% 0 0;
  box-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.taburete-legs {
  display: flex;
  gap: 5px;
  justify-content: center;
}

.taburete-legs span {
  width: 2px;
  height: 20px;
  background: linear-gradient(180deg, #654321 0%, #4a3319 100%);
  border-radius: 1px;
}

.taburete-num {
  margin-top: 0.25rem;
  font-size: 0.7rem;
  font-weight: 600;
  color: #fff;
  background: rgba(255,255,255,0.1);
  padding: 0.15rem 0.35rem;
  border-radius: 3px;
}

.taburete-vertical.disponible .taburete-top,
.taburete-horizontal.disponible .taburete-top {
  background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
}

.taburete-vertical.disponible:hover,
.taburete-horizontal.disponible:hover {
  transform: translateY(-5px);
}

.taburete-vertical.reservada .taburete-top,
.taburete-horizontal.reservada .taburete-top {
  background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
  opacity: 0.6;
  cursor: not-allowed;
}

/* Zona principal: Mesas y Entrada */
.zona-principal {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 2rem;
  align-items: start;
}

/* Camerino */
.camerino {
  background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
  border-radius: 12px;
  padding: 0.75rem 1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  min-width: 120px;
  min-height: 60px;
  max-height: 80px;
}

.camerino-label {
  font-size: 0.9rem;
  font-weight: 700;
  color: white;
  letter-spacing: 1px;
}

/* Escenario */
.escenario {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 12px;
  padding: 0.75rem 1rem;
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  position: relative;
  overflow: hidden;
  min-height: 60px;
  max-height: 80px;
}

.escenario::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, 
    #ff0000 0%, #ff7f00 16%, #ffff00 33%, 
    #00ff00 50%, #0000ff 66%, #4b0082 83%, #9400d3 100%);
  animation: lights 3s linear infinite;
}

@keyframes lights {
  0% { opacity: 0.5; }
  50% { opacity: 1; }
  100% { opacity: 0.5; }
}

.escenario-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
  color: white;
}

.escenario-label {
  font-size: 1.2rem;
  font-weight: 700;
  letter-spacing: 2px;
}

.escenario-lights {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
}

.escenario-lights span {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255,255,255,0.8);
  box-shadow: 0 0 10px rgba(255,255,255,0.6);
  animation: blink 1.5s infinite;
}

.escenario-lights span:nth-child(2) { animation-delay: 0.3s; }
.escenario-lights span:nth-child(3) { animation-delay: 0.6s; }
.escenario-lights span:nth-child(4) { animation-delay: 0.9s; }

@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.3; }
}

/* Barra con taburetes en L */
.barra-container {
  background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
  border-radius: 12px;
  padding: 0.75rem 1rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  position: relative;
  min-height: 60px;
  max-height: 80px;
}

.barra-label {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 1.2rem;
  font-weight: 700;
  color: rgba(255,255,255,0.08);
  letter-spacing: 2px;
  z-index: 0;
  pointer-events: none;
}

.barra-layout-l {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 0;
  position: relative;
  z-index: 1;
}

/* 3 taburetes verticales */
.taburetes-columna-vertical {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  padding-right: 0.4rem;
}

/* 6 taburetes horizontales alineados con el 3er taburete */
.taburetes-fila-horizontal {
  display: flex;
  gap: 0.4rem;
  align-items: flex-end;
  padding-bottom: 0;
}

.taburete-item {
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  padding: 0.25rem;
  border-radius: 6px;
}

.taburete-visual {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2px;
}

/* Área principal: Mesas y Entrada */
.area-principal {
  display: grid;
  grid-template-columns: 1fr 120px;
  gap: 1rem;
  align-items: start;
}

.columna-mesas {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.fila-mesas {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

/* Mesas redondas */
.mesa-item {
  cursor: pointer;
  transition: all 0.3s ease;
}

.mesa-visual {
  width: 90px;
  height: 90px;
  background: linear-gradient(135deg, #8b4513 0%, #654321 100%);
  border-radius: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 15px rgba(0,0,0,0.3);
  position: relative;
  border: 3px solid rgba(255,255,255,0.1);
}

.mesa-visual::before {
  content: '';
  position: absolute;
  top: 15%;
  left: 15%;
  right: 15%;
  bottom: 15%;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.15), transparent);
}

.mesa-numero {
  font-size: 1.3rem;
  font-weight: 700;
  color: white;
  z-index: 1;
  text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.sillas-visual {
  display: flex;
  gap: 4px;
  margin-top: 0.3rem;
  z-index: 1;
}

.silla-mini {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1px;
}

.silla-back-mini {
  width: 8px;
  height: 12px;
  background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
  border-radius: 2px 2px 0 0;
}

.silla-seat-mini {
  width: 10px;
  height: 6px;
  background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
  border-radius: 1px;
}

.silla-mini.disponible .silla-back-mini,
.silla-mini.disponible .silla-seat-mini {
  background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
}

.silla-mini.ocupada .silla-back-mini,
.silla-mini.ocupada .silla-seat-mini {
  background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
}

/* Estados de mesa */
.mesa-item.disponible .mesa-visual {
  border-color: #27ae60;
  box-shadow: 0 6px 15px rgba(39, 174, 96, 0.4);
}

.mesa-item.disponible:hover {
  transform: translateY(-5px) scale(1.05);
}

.mesa-item.disponible:hover .mesa-visual {
  box-shadow: 0 10px 25px rgba(39, 174, 96, 0.6);
}

.mesa-item.parcial .mesa-visual {
  border-color: #f39c12;
  box-shadow: 0 6px 15px rgba(243, 156, 18, 0.4);
}

.mesa-item.parcial:hover {
  transform: translateY(-5px) scale(1.05);
}

.mesa-item.reservada .mesa-visual {
  border-color: #e74c3c;
  box-shadow: 0 6px 15px rgba(231, 76, 60, 0.4);
  opacity: 0.6;
  cursor: not-allowed;
}

/* Entrada/Salida */
.entrada-salida {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 1rem 0.75rem;
  background: rgba(255,255,255,0.05);
  border-radius: 12px;
  align-self: center;
  margin-left: auto;
}

.puerta-visual {
  width: 50px;
  height: 70px;
}

.puerta-marco {
  width: 100%;
  height: 100%;
  background: #8b4513;
  border-radius: 8px 8px 0 0;
  padding: 4px;
  box-shadow: inset 0 2px 8px rgba(0,0,0,0.3);
  position: relative;
}

.puerta-hoja {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #d4a574 0%, #b8956a 100%);
  border-radius: 4px 4px 0 0;
  position: relative;
}

.puerta-hoja::before {
  content: '';
  position: absolute;
  right: 6px;
  top: 50%;
  transform: translateY(-50%);
  width: 6px;
  height: 6px;
  background: #8b4513;
  border-radius: 50%;
  box-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.entrada-texto {
  font-size: 0.75rem;
  font-weight: 700;
  color: white;
  text-align: center;
  line-height: 1.3;
  letter-spacing: 0.5px;
}

/* Modal */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-content h3 {
  margin-bottom: 1rem;
  color: #667eea;
  position: sticky;
  top: 0;
  background: white;
  padding-bottom: 0.5rem;
  z-index: 1;
}

.modal-content p {
  margin-bottom: 0.5rem;
}

/* Selector de sillas */
.sillas-selector {
  margin: 1.5rem 0;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.sillas-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin: 1rem 0;
}

.silla-checkbox {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem;
  border: 2px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s;
  background: white;
}

.silla-checkbox:hover:not(.ocupada) {
  border-color: #667eea;
  background: #f0f4ff;
}

.silla-checkbox.seleccionada {
  border-color: #27ae60;
  background: #e8f5e9;
}

.silla-checkbox.ocupada {
  background: #f8d7da;
  border-color: #e74c3c;
  cursor: not-allowed;
  opacity: 0.6;
}

.silla-checkbox input[type="checkbox"] {
  margin-bottom: 0.5rem;
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.silla-icon {
  font-size: 2rem;
  margin-bottom: 0.25rem;
}

.silla-label {
  font-weight: 500;
  font-size: 0.9rem;
}

.ocupada-label {
  font-size: 0.75rem;
  color: #e74c3c;
  font-weight: bold;
  margin-top: 0.25rem;
}

.mesa-completa-option {
  margin-top: 1rem;
  padding: 1rem;
  background: white;
  border: 2px solid #667eea;
  border-radius: 8px;
}

.mesa-completa-option label {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.mesa-completa-option input[type="checkbox"] {
  margin-right: 0.5rem;
  width: 20px;
  height: 20px;
}

.taburete-info {
  padding: 1rem;
  background: #e8f5e9;
  border-radius: 8px;
  text-align: center;
  font-size: 1.1rem;
  color: #27ae60;
  margin: 1rem 0;
}

.form-group {
  margin: 1rem 0;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input[type="checkbox"] {
  margin-right: 0.5rem;
}

.tipo-entrada {
  margin: 1.5rem 0;
}

.radio-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 0.5rem;
}

.radio-option {
  display: flex;
  align-items: center;
  padding: 1rem;
  border: 2px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s;
}

.radio-option:hover {
  border-color: #667eea;
  background: #f8f9ff;
}

.radio-option input[type="radio"] {
  margin-right: 1rem;
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.radio-option input[type="radio"]:checked ~ .radio-content {
  color: #667eea;
  font-weight: 600;
}

.radio-option.vip-option {
  border-color: #9b59b6;
}

.radio-option.vip-option:hover {
  background: #f8f4ff;
  border-color: #9b59b6;
}

.radio-option input[type="radio"]:checked {
  accent-color: #667eea;
}

.radio-option.vip-option input[type="radio"]:checked {
  accent-color: #9b59b6;
}

.radio-content {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.radio-title {
  font-size: 1rem;
  font-weight: 500;
}

.radio-desc {
  font-size: 0.875rem;
  color: #666;
}

.precio-desglose {
  background: #f8f9fa;
  padding: 1rem;
  border-radius: 4px;
  margin: 1rem 0;
  font-size: 0.9rem;
}

.precio-desglose p {
  margin-bottom: 0.5rem;
}

.precio-desglose strong {
  color: #667eea;
}

.total {
  font-size: 1.5rem;
  margin: 1rem 0;
  color: #27ae60;
  font-weight: bold;
  text-align: center;
  padding: 1rem;
  background: #e8f5e9;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
  position: sticky;
  bottom: 0;
  background: white;
  padding-top: 1rem;
  z-index: 1;
}

.btn-confirmar, .btn-cancelar {
  flex: 1;
  padding: 0.75rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
  transition: all 0.3s;
}

.btn-confirmar {
  background: #27ae60;
  color: white;
}

.btn-confirmar:hover:not(:disabled) {
  background: #229954;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
}

.btn-confirmar:disabled {
  background: #95a5a6;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-cancelar {
  background: #e74c3c;
  color: white;
}

.btn-cancelar:hover {
  background: #c0392b;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
}

/* Responsive */
@media (max-width: 1024px) {
  .fila-superior {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .area-principal {
    grid-template-columns: 1fr;
  }
  
  .entrada-salida {
    flex-direction: row;
    width: 100%;
    justify-content: center;
  }
  
  .barra-layout-l {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: center;
  }
  
  .taburetes-fila-horizontal {
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
  }
  
  .taburetes-columna-vertical {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    padding-right: 0;
    gap: 0.5rem;
  }
  
  .barra-container {
    padding: 1rem;
    min-height: auto;
    max-height: none;
  }
}

@media (max-width: 768px) {
  .croquis-container {
    padding: 1rem;
  }
  
  .local-layout {
    gap: 1.5rem;
  }
  
  .fila-mesas {
    gap: 0.75rem;
  }
  
  .mesa-visual {
    width: 70px;
    height: 70px;
  }
  
  .mesa-numero {
    font-size: 1rem;
  }
  
  .sillas-visual {
    gap: 2px;
  }
  
  .silla-back-mini {
    width: 6px;
    height: 10px;
  }
  
  .silla-seat-mini {
    width: 8px;
    height: 5px;
  }
  
  .sillas-grid {
    grid-template-columns: 1fr;
  }
  
  .escenario-label {
    font-size: 1rem;
    letter-spacing: 1px;
  }
  
  .barra-label {
    font-size: 1rem;
    letter-spacing: 1px;
  }
  
  .camerino-label {
    font-size: 0.8rem;
  }
  
  .taburete-top {
    width: 22px;
    height: 6px;
  }
  
  .taburete-legs span {
    width: 2px;
    height: 12px;
  }
  
  .taburete-num {
    font-size: 0.6rem;
    padding: 0.1rem 0.2rem;
  }
  
  .taburete-item {
    padding: 0.2rem;
  }
  
  .taburetes-columna-vertical,
  .taburetes-fila-horizontal {
    gap: 0.3rem;
  }
  
  .barra-container {
    padding: 0.75rem;
  }
  
  .modal-content {
    padding: 1.5rem;
    max-height: 85vh;
  }
  
  .modal-content h3 {
    font-size: 1.3rem;
  }
  
  .leyenda {
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .leyenda-items {
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
  }
  
  .leyenda-note {
    font-size: 0.85rem;
  }
  
  h1 {
    font-size: 1.5rem;
  }
  
  .funcion-info h2 {
    font-size: 1.3rem;
  }
  
  .navbar-brand {
    gap: 0.5rem;
  }
  
  .navbar-logo {
    height: 35px;
  }
  
  .logo {
    font-size: 1.2rem;
  }
  
  .nav-links {
    gap: 0.75rem;
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .croquis-container {
    padding: 0.75rem;
  }
  
  .mesa-visual {
    width: 60px;
    height: 60px;
  }
  
  .mesa-numero {
    font-size: 0.9rem;
  }
  
  .fila-mesas {
    gap: 0.5rem;
  }
  
  .local-layout {
    gap: 1rem;
  }
  
  .escenario {
    min-height: 50px;
    max-height: 60px;
    padding: 0.5rem;
  }
  
  .camerino {
    min-height: 50px;
    max-height: 60px;
    padding: 0.5rem;
  }
  
  .barra-container {
    min-height: auto;
    max-height: none;
    padding: 0.5rem;
  }
  
  .escenario-label {
    font-size: 0.85rem;
  }
  
  .barra-label {
    font-size: 0.85rem;
  }
  
  .camerino-label {
    font-size: 0.7rem;
  }
  
  .taburete-item {
    padding: 0.15rem;
  }
  
  .taburete-top {
    width: 20px;
    height: 5px;
  }
  
  .taburete-legs span {
    width: 1.5px;
    height: 10px;
  }
  
  .taburete-num {
    font-size: 0.55rem;
    padding: 0.1rem 0.15rem;
  }
  
  .taburetes-columna-vertical,
  .taburetes-fila-horizontal {
    gap: 0.25rem;
  }
  
  .modal-content {
    padding: 1rem;
  }
  
  .btn-confirmar, .btn-cancelar {
    padding: 0.6rem;
    font-size: 0.9rem;
  }
  
  h1 {
    font-size: 1.3rem;
  }
  
  .navbar {
    padding: 0.75rem 0;
  }
  
  .container {
    padding: 0 1rem;
  }
  
  .navbar-logo {
    height: 30px;
  }
  
  .logo {
    font-size: 1rem;
  }
  
  .nav-links a {
    font-size: 0.85rem;
  }
}
</style>
