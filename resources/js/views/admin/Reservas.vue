<template>
  <div class="admin-page">
    <div class="admin-sidebar">
      <h2>🎭 MERAKI Admin</h2>
      <nav>
        <router-link to="/admin">Dashboard</router-link>
        <router-link to="/admin/funciones">Funciones</router-link>
        <router-link to="/admin/reservas">Reservas</router-link>
        <router-link to="/">Ver Sitio</router-link>
      </nav>
    </div>

    <div class="admin-content">
      <div class="header">
        <h1>📋 Gestión de Reservas</h1>
        <div class="header-actions">
          <button @click="exportReservas" class="btn-export">
            📥 Exportar CSV
          </button>
          <button @click="loadReservas" class="btn-refresh">
            🔄 Actualizar
          </button>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📊</div>
          <div class="stat-content">
            <div class="stat-value">{{ stats.total_reservas }}</div>
            <div class="stat-label">Total Reservas</div>
          </div>
        </div>
        <div class="stat-card success">
          <div class="stat-icon">✅</div>
          <div class="stat-content">
            <div class="stat-value">{{ stats.confirmadas }}</div>
            <div class="stat-label">Confirmadas</div>
          </div>
        </div>
        <div class="stat-card warning">
          <div class="stat-icon">⏳</div>
          <div class="stat-content">
            <div class="stat-value">{{ stats.pendientes }}</div>
            <div class="stat-label">Pendientes</div>
          </div>
        </div>
        <div class="stat-card info">
          <div class="stat-icon">💰</div>
          <div class="stat-content">
            <div class="stat-value">Bs. {{ stats.ingresos_totales?.toFixed(2) }}</div>
            <div class="stat-label">Ingresos</div>
          </div>
        </div>
      </div>


      <!-- Filtros -->
      <div class="filters-section">
        <div class="filters-grid">
          <div class="filter-group">
            <label>🔍 Buscar</label>
            <input 
              v-model="filters.search" 
              @input="debounceSearch"
              placeholder="Código, cliente, email..."
            />
          </div>

          <div class="filter-group">
            <label>📅 Fecha Desde</label>
            <input v-model="filters.fecha_desde" type="date" @change="loadReservas" />
          </div>

          <div class="filter-group">
            <label>📅 Fecha Hasta</label>
            <input v-model="filters.fecha_hasta" type="date" @change="loadReservas" />
          </div>

          <div class="filter-group">
            <label>🎭 Función</label>
            <select v-model="filters.funcion_id" @change="loadReservas">
              <option value="">Todas</option>
              <option v-for="funcion in funciones" :key="funcion.id" :value="funcion.id">
                {{ funcion.nombre }} - {{ formatDate(funcion.fecha) }}
              </option>
            </select>
          </div>

          <div class="filter-group">
            <label>📊 Estado</label>
            <select v-model="filters.estado" @change="loadReservas">
              <option value="">Todos</option>
              <option value="pendiente">Pendiente</option>
              <option value="confirmada">Confirmada</option>
              <option value="cancelada">Cancelada</option>
              <option value="no_show">No Show</option>
            </select>
          </div>

          <div class="filter-group">
            <label>⭐ Tipo</label>
            <select v-model="filters.es_vip" @change="loadReservas">
              <option value="">Todos</option>
              <option value="true">VIP</option>
              <option value="false">Normal</option>
            </select>
          </div>

          <div class="filter-group">
            <label>🍽️ Cena</label>
            <select v-model="filters.incluye_cena" @change="loadReservas">
              <option value="">Todos</option>
              <option value="true">Con Cena</option>
              <option value="false">Sin Cena</option>
            </select>
          </div>

          <div class="filter-group">
            <button @click="clearFilters" class="btn-clear">
              🗑️ Limpiar Filtros
            </button>
          </div>
        </div>
      </div>


      <!-- Tabla de Reservas -->
      <div class="table-container">
        <div v-if="loading" class="loading">
          <div class="spinner"></div>
          <p>Cargando reservas...</p>
        </div>

        <table v-else class="data-table">
          <thead>
            <tr>
              <th>Código</th>
              <th>Cliente</th>
              <th>Función</th>
              <th>Fecha</th>
              <th>Mesa</th>
              <th>Personas</th>
              <th>Tipo</th>
              <th>Monto</th>
              <th>Estado</th>
              <th>Check-in</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="reserva in reservas.data" :key="reserva.id">
              <td>
                <span class="codigo">{{ reserva.codigo_reserva }}</span>
              </td>
              <td>
                <div class="cliente-info">
                  <strong>{{ reserva.user?.name }}</strong>
                  <small>{{ reserva.user?.email }}</small>
                </div>
              </td>
              <td>{{ reserva.funcion?.nombre }}</td>
              <td>{{ formatDate(reserva.funcion?.fecha) }}</td>
              <td>
                <span class="mesa-badge">
                  Mesa {{ reserva.mesa?.numero }}
                  <small v-if="reserva.mesa_completa">(Completa)</small>
                  <small v-else>({{ reserva.sillas_reservadas?.length }} sillas)</small>
                </span>
              </td>
              <td>{{ reserva.num_personas }}</td>
              <td>
                <div class="tipo-badges">
                  <span v-if="reserva.es_vip" class="badge badge-vip">VIP</span>
                  <span v-else class="badge badge-normal">Normal</span>
                  <span v-if="reserva.incluye_cena" class="badge badge-cena">🍽️ Cena</span>
                </div>
              </td>
              <td class="monto">Bs. {{ reserva.monto_total }}</td>
              <td>
                <span :class="`badge badge-${reserva.estado}`">
                  {{ estadoLabel(reserva.estado) }}
                </span>
              </td>
              <td>
                <span v-if="reserva.check_in_at" class="check-in-badge">
                  ✅ {{ formatDateTime(reserva.check_in_at) }}
                </span>
                <span v-else class="check-in-badge pending">⏳ Pendiente</span>
              </td>
              <td>
                <div class="actions">
                  <button @click="viewDetails(reserva)" class="btn-action btn-view" title="Ver Detalles">
                    👁️
                  </button>
                  <button 
                    v-if="reserva.estado === 'pendiente'"
                    @click="confirmReserva(reserva)" 
                    class="btn-action btn-confirm" 
                    title="Confirmar"
                  >
                    ✅
                  </button>
                  <button 
                    v-if="!reserva.check_in_at && reserva.estado === 'confirmada'"
                    @click="checkIn(reserva)" 
                    class="btn-action btn-checkin" 
                    title="Check-in"
                  >
                    📥
                  </button>
                  <button 
                    v-if="reserva.estado === 'confirmada' && !reserva.check_in_at"
                    @click="markNoShow(reserva)" 
                    class="btn-action btn-noshow" 
                    title="No Show"
                  >
                    ❌
                  </button>
                  <button 
                    v-if="['pendiente', 'confirmada'].includes(reserva.estado)"
                    @click="cancelReserva(reserva)" 
                    class="btn-action btn-cancel" 
                    title="Cancelar"
                  >
                    🚫
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Paginación -->
        <div v-if="reservas.data?.length" class="pagination">
          <button 
            @click="changePage(reservas.current_page - 1)" 
            :disabled="!reservas.prev_page_url"
            class="btn-page"
          >
            ← Anterior
          </button>
          <span class="page-info">
            Página {{ reservas.current_page }} de {{ reservas.last_page }}
            ({{ reservas.total }} reservas)
          </span>
          <button 
            @click="changePage(reservas.current_page + 1)" 
            :disabled="!reservas.next_page_url"
            class="btn-page"
          >
            Siguiente →
          </button>
        </div>
      </div>


      <!-- Modal de Detalles -->
      <div v-if="showDetailsModal" class="modal" @click.self="closeDetailsModal">
        <div class="modal-content modal-large">
          <button @click="closeDetailsModal" class="close-btn">✕</button>
          
          <h2>📋 Detalle de Reserva</h2>
          
          <div v-if="selectedReserva" class="details-grid">
            <!-- Información de la Reserva -->
            <div class="detail-section">
              <h3>🎫 Información de Reserva</h3>
              <div class="detail-item">
                <span class="label">Código:</span>
                <span class="value codigo-large">{{ selectedReserva.codigo_reserva }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Estado:</span>
                <span :class="`badge badge-${selectedReserva.estado}`">
                  {{ estadoLabel(selectedReserva.estado) }}
                </span>
              </div>
              <div class="detail-item">
                <span class="label">Fecha de Reserva:</span>
                <span class="value">{{ formatDateTime(selectedReserva.created_at) }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Check-in:</span>
                <span v-if="selectedReserva.check_in_at" class="value">
                  ✅ {{ formatDateTime(selectedReserva.check_in_at) }}
                </span>
                <span v-else class="value text-muted">⏳ Pendiente</span>
              </div>
            </div>

            <!-- Información del Cliente -->
            <div class="detail-section">
              <h3>👤 Cliente</h3>
              <div class="detail-item">
                <span class="label">Nombre:</span>
                <span class="value">{{ selectedReserva.user?.name }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Email:</span>
                <span class="value">{{ selectedReserva.user?.email }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Teléfono:</span>
                <span class="value">{{ selectedReserva.user?.phone || 'No registrado' }}</span>
              </div>
            </div>

            <!-- Información de la Función -->
            <div class="detail-section">
              <h3>🎭 Función</h3>
              <div class="detail-item">
                <span class="label">Nombre:</span>
                <span class="value">{{ selectedReserva.funcion?.nombre }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Artista:</span>
                <span class="value">{{ selectedReserva.funcion?.artista || 'N/A' }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Fecha:</span>
                <span class="value">{{ formatDate(selectedReserva.funcion?.fecha) }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Horario:</span>
                <span class="value">{{ selectedReserva.funcion?.horario }}</span>
              </div>
            </div>

            <!-- Información de la Mesa -->
            <div class="detail-section">
              <h3>🪑 Mesa y Ubicación</h3>
              <div class="detail-item">
                <span class="label">Mesa:</span>
                <span class="value">Mesa {{ selectedReserva.mesa?.numero }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Capacidad:</span>
                <span class="value">{{ selectedReserva.mesa?.capacidad }} personas</span>
              </div>
              <div class="detail-item">
                <span class="label">Tipo:</span>
                <span class="value">{{ selectedReserva.mesa?.tipo }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Reserva:</span>
                <span v-if="selectedReserva.mesa_completa" class="value">
                  Mesa Completa
                </span>
                <span v-else class="value">
                  Sillas: {{ selectedReserva.sillas_reservadas?.join(', ') }}
                </span>
              </div>
            </div>

            <!-- Servicios y Montos -->
            <div class="detail-section">
              <h3>💰 Servicios y Montos</h3>
              <div class="detail-item">
                <span class="label">Personas:</span>
                <span class="value">{{ selectedReserva.num_personas }}</span>
              </div>
              <div class="detail-item">
                <span class="label">Tipo de Entrada:</span>
                <span v-if="selectedReserva.es_vip" class="badge badge-vip">VIP (Bs. 50/persona)</span>
                <span v-else class="badge badge-normal">Normal (Bs. 40/persona)</span>
              </div>
              <div class="detail-item">
                <span class="label">Cena:</span>
                <span v-if="selectedReserva.incluye_cena" class="badge badge-cena">
                  Sí (+Bs. 30/persona)
                </span>
                <span v-else class="text-muted">No</span>
              </div>
              <div class="detail-item total">
                <span class="label">Monto Total:</span>
                <span class="value amount">Bs. {{ selectedReserva.monto_total }}</span>
              </div>
            </div>

            <!-- Notas -->
            <div v-if="selectedReserva.notas" class="detail-section full-width">
              <h3>📝 Notas</h3>
              <p class="notas">{{ selectedReserva.notas }}</p>
            </div>
          </div>

          <div class="modal-actions">
            <button @click="closeDetailsModal" class="btn-close">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const reservas = ref({ data: [], current_page: 1, last_page: 1, total: 0 });
const funciones = ref([]);
const stats = ref({});
const loading = ref(false);
const showDetailsModal = ref(false);
const selectedReserva = ref(null);

const filters = ref({
  search: '',
  fecha_desde: '',
  fecha_hasta: '',
  funcion_id: '',
  estado: '',
  es_vip: '',
  incluye_cena: '',
  page: 1,
});

let searchTimeout = null;

const loadReservas = async () => {
  try {
    loading.value = true;
    const params = new URLSearchParams();
    
    Object.keys(filters.value).forEach(key => {
      if (filters.value[key]) {
        params.append(key, filters.value[key]);
      }
    });

    const response = await axios.get(`/api/reservas?${params.toString()}`);
    reservas.value = response.data;
  } catch (error) {
    console.error('Error cargando reservas:', error);
    alert('Error al cargar reservas');
  } finally {
    loading.value = false;
  }
};

const loadFunciones = async () => {
  try {
    const response = await axios.get('/api/funciones');
    funciones.value = response.data;
  } catch (error) {
    console.error('Error cargando funciones:', error);
  }
};

const loadStats = async () => {
  try {
    const params = new URLSearchParams();
    if (filters.value.fecha_desde) params.append('fecha_desde', filters.value.fecha_desde);
    if (filters.value.fecha_hasta) params.append('fecha_hasta', filters.value.fecha_hasta);
    
    const response = await axios.get(`/api/reservas-stats?${params.toString()}`);
    stats.value = response.data;
  } catch (error) {
    console.error('Error cargando estadísticas:', error);
  }
};

const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    loadReservas();
  }, 500);
};

const clearFilters = () => {
  filters.value = {
    search: '',
    fecha_desde: '',
    fecha_hasta: '',
    funcion_id: '',
    estado: '',
    es_vip: '',
    incluye_cena: '',
    page: 1,
  };
  loadReservas();
  loadStats();
};

const changePage = (page) => {
  filters.value.page = page;
  loadReservas();
};

const confirmReserva = async (reserva) => {
  if (!confirm(`¿Confirmar reserva ${reserva.codigo_reserva}?`)) return;
  
  try {
    await axios.post(`/api/reservas/${reserva.id}/confirm`);
    loadReservas();
    loadStats();
  } catch (error) {
    alert('Error al confirmar reserva');
  }
};

const cancelReserva = async (reserva) => {
  if (!confirm(`¿Cancelar reserva ${reserva.codigo_reserva}?`)) return;
  
  try {
    await axios.post(`/api/reservas/${reserva.id}/cancel`);
    loadReservas();
    loadStats();
  } catch (error) {
    alert('Error al cancelar reserva');
  }
};

const checkIn = async (reserva) => {
  if (!confirm(`¿Realizar check-in de ${reserva.codigo_reserva}?`)) return;
  
  try {
    await axios.post(`/api/reservas/${reserva.id}/check-in`);
    loadReservas();
    loadStats();
  } catch (error) {
    alert('Error al realizar check-in');
  }
};

const markNoShow = async (reserva) => {
  if (!confirm(`¿Marcar como no-show ${reserva.codigo_reserva}?`)) return;
  
  try {
    await axios.post(`/api/reservas/${reserva.id}/no-show`);
    loadReservas();
    loadStats();
  } catch (error) {
    alert('Error al marcar como no-show');
  }
};

const viewDetails = async (reserva) => {
  try {
    const response = await axios.get(`/api/reservas/${reserva.id}`);
    selectedReserva.value = response.data;
    showDetailsModal.value = true;
  } catch (error) {
    alert('Error al cargar detalles');
  }
};

const closeDetailsModal = () => {
  showDetailsModal.value = false;
  selectedReserva.value = null;
};

const exportReservas = () => {
  const params = new URLSearchParams();
  Object.keys(filters.value).forEach(key => {
    if (filters.value[key] && key !== 'page') {
      params.append(key, filters.value[key]);
    }
  });
  
  window.location.href = `/api/reservas-export?${params.toString()}`;
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('es-BO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const formatDateTime = (datetime) => {
  if (!datetime) return 'N/A';
  return new Date(datetime).toLocaleString('es-BO', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const estadoLabel = (estado) => {
  const labels = {
    pendiente: 'Pendiente',
    confirmada: 'Confirmada',
    cancelada: 'Cancelada',
    no_show: 'No Show',
  };
  return labels[estado] || estado;
};

onMounted(() => {
  loadReservas();
  loadFunciones();
  loadStats();
});
</script>


<style scoped>
.admin-page {
  display: flex;
  min-height: 100vh;
  background: #f5f7fa;
}

.admin-sidebar {
  width: 250px;
  background: #1a1a1a;
  color: white;
  padding: 2rem 1rem;
  position: fixed;
  height: 100vh;
}

.admin-sidebar h2 {
  margin-bottom: 2rem;
  font-size: 1.5rem;
}

.admin-sidebar nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.admin-sidebar a {
  color: white;
  text-decoration: none;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  transition: all 0.3s;
}

.admin-sidebar a:hover,
.admin-sidebar a.router-link-active {
  background: #667eea;
  transform: translateX(5px);
}

.admin-content {
  flex: 1;
  margin-left: 250px;
  padding: 2rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.header h1 {
  font-size: 2rem;
  color: #1a1a1a;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.btn-export, .btn-refresh {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-export {
  background: #27ae60;
  color: white;
}

.btn-export:hover {
  background: #229954;
  transform: translateY(-2px);
}

.btn-refresh {
  background: #3498db;
  color: white;
}

.btn-refresh:hover {
  background: #2980b9;
  transform: translateY(-2px);
}

/* Estadísticas */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}

.stat-card:hover {
  transform: translateY(-4px);
}

.stat-card.success {
  border-left: 4px solid #27ae60;
}

.stat-card.warning {
  border-left: 4px solid #f39c12;
}

.stat-card.info {
  border-left: 4px solid #3498db;
}

.stat-icon {
  font-size: 2.5rem;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #1a1a1a;
}

.stat-label {
  font-size: 0.875rem;
  color: #666;
  text-transform: uppercase;
}

/* Filtros */
.filters-section {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.filter-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #495057;
  font-size: 0.875rem;
}

.filter-group input,
.filter-group select {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

.filter-group input:focus,
.filter-group select:focus {
  outline: none;
  border-color: #667eea;
}

.btn-clear {
  width: 100%;
  padding: 0.75rem;
  background: #e74c3c;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
  margin-top: 1.7rem;
}

.btn-clear:hover {
  background: #c0392b;
}


/* Tabla */
.table-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.loading {
  text-align: center;
  padding: 4rem;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #667eea;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #e9ecef;
}

.data-table th {
  background: #f8f9fa;
  font-weight: 600;
  color: #495057;
  text-transform: uppercase;
  font-size: 0.875rem;
}

.data-table tbody tr {
  transition: background 0.2s;
}

.data-table tbody tr:hover {
  background: #f8f9fa;
}

.codigo {
  font-family: monospace;
  background: #e7f3ff;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-weight: 600;
  color: #0066cc;
}

.cliente-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.cliente-info strong {
  color: #1a1a1a;
}

.cliente-info small {
  color: #666;
  font-size: 0.875rem;
}

.mesa-badge {
  background: #f8f9fa;
  padding: 0.5rem;
  border-radius: 6px;
  display: inline-block;
}

.mesa-badge small {
  display: block;
  font-size: 0.75rem;
  color: #666;
  margin-top: 0.25rem;
}

.tipo-badges {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 600;
  display: inline-block;
}

.badge-vip {
  background: #f39c12;
  color: white;
}

.badge-normal {
  background: #95a5a6;
  color: white;
}

.badge-cena {
  background: #e74c3c;
  color: white;
}

.badge-pendiente {
  background: #f39c12;
  color: white;
}

.badge-confirmada {
  background: #27ae60;
  color: white;
}

.badge-cancelada {
  background: #95a5a6;
  color: white;
}

.badge-no_show {
  background: #e74c3c;
  color: white;
}

.monto {
  font-weight: 700;
  color: #27ae60;
  font-size: 1.1rem;
}

.check-in-badge {
  font-size: 0.875rem;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  background: #d4edda;
  color: #155724;
}

.check-in-badge.pending {
  background: #fff3cd;
  color: #856404;
}

.actions {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.btn-action {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-view {
  background: #3498db;
}

.btn-confirm {
  background: #27ae60;
}

.btn-checkin {
  background: #9b59b6;
}

.btn-noshow {
  background: #e67e22;
}

.btn-cancel {
  background: #e74c3c;
}

.btn-action:hover {
  transform: scale(1.1);
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

/* Paginación */
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  background: #f8f9fa;
}

.btn-page {
  padding: 0.75rem 1.5rem;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-page:hover:not(:disabled) {
  background: #5568d3;
  transform: translateY(-2px);
}

.btn-page:disabled {
  background: #e9ecef;
  color: #adb5bd;
  cursor: not-allowed;
}

.page-info {
  color: #495057;
  font-weight: 600;
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
  backdrop-filter: blur(4px);
}

.modal-content {
  background: white;
  border-radius: 16px;
  max-width: 900px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  padding: 2rem;
  position: relative;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: #e74c3c;
  color: white;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  transition: all 0.3s;
}

.close-btn:hover {
  background: #c0392b;
  transform: rotate(90deg);
}

.modal-content h2 {
  margin-bottom: 2rem;
  color: #1a1a1a;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.detail-section {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 1.5rem;
}

.detail-section.full-width {
  grid-column: 1 / -1;
}

.detail-section h3 {
  margin-bottom: 1rem;
  color: #495057;
  font-size: 1.1rem;
  border-bottom: 2px solid #dee2e6;
  padding-bottom: 0.5rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #e9ecef;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-item .label {
  font-weight: 600;
  color: #495057;
}

.detail-item .value {
  color: #1a1a1a;
  text-align: right;
}

.detail-item.total {
  background: #e7f3ff;
  padding: 1rem;
  border-radius: 8px;
  margin-top: 0.5rem;
}

.detail-item.total .label {
  font-size: 1.2rem;
}

.detail-item.total .value.amount {
  font-size: 1.5rem;
  font-weight: 700;
  color: #27ae60;
}

.codigo-large {
  font-family: monospace;
  font-size: 1.2rem;
  background: #e7f3ff;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  color: #0066cc;
  font-weight: 700;
}

.text-muted {
  color: #6c757d;
}

.notas {
  background: white;
  padding: 1rem;
  border-radius: 8px;
  border-left: 4px solid #667eea;
  line-height: 1.6;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
}

.btn-close {
  padding: 0.75rem 2rem;
  background: #95a5a6;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-close:hover {
  background: #7f8c8d;
}

@media (max-width: 768px) {
  .admin-content {
    margin-left: 0;
    padding: 1rem;
  }

  .admin-sidebar {
    display: none;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .filters-grid {
    grid-template-columns: 1fr;
  }

  .data-table {
    font-size: 0.875rem;
  }

  .data-table th,
  .data-table td {
    padding: 0.5rem;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }
}
</style>
