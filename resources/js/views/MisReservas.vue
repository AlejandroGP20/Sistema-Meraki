<template>
  <div class="mis-reservas-page">
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
      <h1>Mis Reservas</h1>
      
      <div v-if="reservas.length === 0" class="empty">
        <p>No tienes reservas aún</p>
        <router-link to="/funciones" class="btn-primary">Ver Funciones</router-link>
      </div>

      <div v-else class="reservas-list">
        <div v-for="reserva in reservas" :key="reserva.id" class="reserva-card">
          <div class="reserva-header">
            <h3>{{ reserva.funcion.nombre }}</h3>
            <span :class="`badge badge-${reserva.estado}`">{{ reserva.estado }}</span>
          </div>
          <p><strong>Código:</strong> {{ reserva.codigo_reserva }}</p>
          <p><strong>Fecha:</strong> {{ formatDate(reserva.funcion.fecha) }}</p>
          <p><strong>Horario:</strong> {{ reserva.funcion.horario }}</p>
          <p><strong>Mesa:</strong> {{ reserva.mesa.numero }} ({{ reserva.mesa.zona }})</p>
          <p><strong>Personas:</strong> {{ reserva.num_personas }}</p>
          <p><strong>Total:</strong> Bs. {{ reserva.monto_total }}</p>
          
          <div class="reserva-actions">
            <button 
              v-if="reserva.estado === 'confirmada'" 
              @click="cancelarReserva(reserva.id)"
              class="btn-cancelar"
            >
              Cancelar Reserva
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const reservas = ref([]);

const loadReservas = async () => {
  try {
    const response = await axios.get('/api/reservas');
    reservas.value = response.data;
  } catch (error) {
    console.error('Error al cargar reservas:', error);
  }
};

const cancelarReserva = async (id) => {
  if (!confirm('¿Estás seguro de cancelar esta reserva?')) return;
  
  try {
    const response = await axios.post(`/api/reservas/${id}/cancel`);
    alert(response.data.message || 'Reserva cancelada exitosamente');
    loadReservas();
  } catch (error) {
    console.error('Error al cancelar la reserva:', error);
    const mensaje = error.response?.data?.message || 'Error al cancelar la reserva';
    alert(mensaje);
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-BO');
};

onMounted(() => {
  loadReservas();
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
  max-width: 1200px;
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

.navbar a {
  color: white;
  text-decoration: none;
}

h1 {
  margin-bottom: 2rem;
}

.empty {
  text-align: center;
  padding: 3rem;
}

.btn-primary {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: #667eea;
  color: white;
  text-decoration: none;
  border-radius: 4px;
}

.reservas-list {
  display: grid;
  gap: 1.5rem;
}

.reserva-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.reserva-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 500;
}

.badge-confirmada {
  background: #27ae60;
  color: white;
}

.badge-cancelada {
  background: #e74c3c;
  color: white;
}

.reserva-card p {
  margin-bottom: 0.5rem;
}

.reserva-actions {
  margin-top: 1rem;
}

.btn-cancelar {
  padding: 0.5rem 1rem;
  background: #e74c3c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
