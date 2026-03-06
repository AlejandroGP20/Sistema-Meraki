<template>
  <div class="funciones-page">
    <nav class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <img src="/images/MerakiLogo.jpg" alt="MERAKI Logo" class="navbar-logo" />
          <router-link to="/" class="logo">MERAKI</router-link>
        </div>
        <div class="nav-links">
          <router-link to="/">Inicio</router-link>
          <router-link v-if="authStore.isAuthenticated" to="/mis-reservas">Mis Reservas</router-link>
        </div>
      </div>
    </nav>

    <div class="container">
      <h1>Funciones Disponibles</h1>
      
      <div class="funciones-grid">
        <div v-for="funcion in funciones" :key="funcion.id" class="funcion-card">
          <div class="funcion-info">
            <h3>{{ funcion.nombre }}</h3>
            <p class="artista">{{ funcion.artista }}</p>
            <p class="genero">{{ funcion.genero_musical }}</p>
            <p class="fecha">📅 {{ formatDate(funcion.fecha) }}</p>
            <p class="horario">🕐 {{ funcion.horario }}</p>
            <p class="precio">💰 Desde Bs. {{ funcion.precio_entrada }}</p>
          </div>
          <router-link 
            :to="`/reservar/${funcion.id}`" 
            class="btn-reservar"
            v-if="funcion.estado === 'activo'"
          >
            Reservar
          </router-link>
          <span v-else class="badge-agotado">Agotado</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';

const authStore = useAuthStore();
const funciones = ref([]);

const loadFunciones = async () => {
  try {
    const response = await axios.get('/api/funciones');
    funciones.value = response.data;
  } catch (error) {
    console.error('Error al cargar funciones:', error);
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
  loadFunciones();
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
}

.nav-links a {
  color: white;
  text-decoration: none;
}

h1 {
  margin-bottom: 2rem;
  text-align: center;
}

.funciones-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.funcion-card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.funcion-info h3 {
  margin-bottom: 0.5rem;
  color: #667eea;
}

.artista {
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.genero {
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 1rem;
}

.fecha, .horario, .precio {
  margin-bottom: 0.5rem;
}

.btn-reservar {
  display: block;
  width: 100%;
  padding: 0.75rem;
  background: #667eea;
  color: white;
  text-align: center;
  text-decoration: none;
  border-radius: 4px;
  margin-top: 1rem;
}

.badge-agotado {
  display: block;
  text-align: center;
  padding: 0.75rem;
  background: #e74c3c;
  color: white;
  border-radius: 4px;
  margin-top: 1rem;
}
</style>
