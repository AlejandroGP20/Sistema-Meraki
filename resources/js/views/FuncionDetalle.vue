<template>
  <div class="detalle-page">
    <nav class="navbar">
      <div class="container">
        <router-link to="/" class="logo">🎭 MERAKI</router-link>
        <router-link to="/funciones">Funciones</router-link>
      </div>
    </nav>

    <div class="container" v-if="funcion">
      <h1>{{ funcion.nombre }}</h1>
      <p class="artista">{{ funcion.artista }}</p>
      <p>{{ formatDate(funcion.fecha) }} - {{ funcion.horario }}</p>
      <router-link :to="`/reservar/${funcion.id}`" class="btn-primary">Reservar</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const funcion = ref(null);

onMounted(async () => {
  const response = await axios.get(`/api/funciones/${route.params.id}`);
  funcion.value = response.data;
});

const formatDate = (date) => new Date(date).toLocaleDateString('es-BO');
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
  gap: 2rem;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
}

.navbar a {
  color: white;
  text-decoration: none;
}

.artista {
  font-size: 1.2rem;
  color: #666;
  margin: 1rem 0;
}

.btn-primary {
  display: inline-block;
  margin-top: 2rem;
  padding: 1rem 2rem;
  background: #667eea;
  color: white;
  text-decoration: none;
  border-radius: 4px;
}
</style>
