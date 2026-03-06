<template>
  <div class="admin-page">
    <div class="admin-sidebar">
      <h2> MERAKI Admin</h2>
      <nav>
        <router-link to="/admin">Dashboard</router-link>
        <router-link to="/admin/funciones">Funciones</router-link>
        <router-link to="/admin/reservas">Reservas</router-link>
        <router-link to="/">Ver Sitio</router-link>
      </nav>
    </div>

    <div class="admin-content">
      <h1>Gestión de Reservas</h1>

      <table class="data-table">
        <thead>
          <tr>
            <th>Código</th>
            <th>Cliente</th>
            <th>Función</th>
            <th>Mesa</th>
            <th>Personas</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reserva in reservas" :key="reserva.id">
            <td>{{ reserva.codigo_reserva }}</td>
            <td>{{ reserva.user.name }}</td>
            <td>{{ reserva.funcion.nombre }}</td>
            <td>{{ reserva.mesa.numero }}</td>
            <td>{{ reserva.num_personas }}</td>
            <td>Bs. {{ reserva.monto_total }}</td>
            <td><span :class="`badge badge-${reserva.estado}`">{{ reserva.estado }}</span></td>
            <td>
              <button 
                v-if="!reserva.check_in_at && reserva.estado === 'confirmada'" 
                @click="checkIn(reserva.id)"
                class="btn-checkin"
              >
                Check-in
              </button>
              <button 
                v-if="reserva.estado === 'confirmada'" 
                @click="cancelar(reserva.id)"
                class="btn-cancel-reserva"
              >
                Cancelar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const reservas = ref([]);

const loadReservas = async () => {
  const response = await axios.get('/api/reservas');
  reservas.value = response.data;
};

const checkIn = async (id) => {
  try {
    await axios.post(`/api/reservas/${id}/check-in`);
    alert('Check-in realizado');
    loadReservas();
  } catch (error) {
    alert('Error al realizar check-in');
  }
};

const cancelar = async (id) => {
  if (!confirm('¿Cancelar reserva?')) return;
  await axios.post(`/api/reservas/${id}/cancel`);
  loadReservas();
};

onMounted(() => {
  loadReservas();
});
</script>

<style scoped>
.admin-page {
  display: flex;
  min-height: 100vh;
}

.admin-sidebar {
  width: 250px;
  background: #1a1a1a;
  color: white;
  padding: 2rem 1rem;
}

.admin-sidebar h2 {
  margin-bottom: 2rem;
}

.admin-sidebar nav {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.admin-sidebar a {
  color: white;
  text-decoration: none;
  padding: 0.75rem 1rem;
  border-radius: 4px;
}

.admin-sidebar a:hover,
.admin-sidebar a.router-link-active {
  background: #667eea;
}

.admin-content {
  flex: 1;
  padding: 2rem;
}

.data-table {
  width: 100%;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  margin-top: 2rem;
}

.data-table th,
.data-table td {
  padding: 1rem;
  text-align: left;
}

.data-table th {
  background: #f5f5f5;
  font-weight: 600;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
}

.badge-confirmada {
  background: #27ae60;
  color: white;
}

.badge-cancelada {
  background: #e74c3c;
  color: white;
}

.btn-checkin, .btn-cancel-reserva {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 0.5rem;
}

.btn-checkin {
  background: #3498db;
  color: white;
}

.btn-cancel-reserva {
  background: #e74c3c;
  color: white;
}
</style>
