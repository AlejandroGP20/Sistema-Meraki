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
      <div class="page-header">
        <h1>🎭 Funciones Disponibles</h1>
        <p class="subtitle">Elige tu próxima experiencia en MERAKI</p>
      </div>
      
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
        <p>Cargando funciones...</p>
      </div>

      <div v-else-if="funciones.length === 0" class="no-funciones">
        <span class="icon">🎪</span>
        <h2>No hay funciones disponibles</h2>
        <p>Vuelve pronto para ver nuestros próximos eventos</p>
      </div>

      <div v-else class="funciones-grid">
        <div v-for="funcion in funciones" :key="funcion.id" class="funcion-card">
          <!-- Imagen -->
          <div class="funcion-image">
            <img 
              v-if="funcion.imagen_principal" 
              :src="`/storage/${funcion.imagen_principal.ruta}`" 
              :alt="funcion.imagen_principal.alt_text || funcion.nombre"
              loading="lazy"
            />
            <div v-else class="funcion-placeholder">
              <span class="placeholder-icon">🎭</span>
              <p>{{ funcion.nombre }}</p>
            </div>
            
            <!-- Badge de género -->
            <div v-if="funcion.genero_musical" class="genre-badge">
              {{ funcion.genero_musical }}
            </div>

            <!-- Badge de estado -->
            <div v-if="funcion.estado !== 'activo'" class="status-badge">
              Agotado
            </div>

            <!-- Contador de fotos -->
            <div v-if="funcion.imagenes && funcion.imagenes.length > 1" class="photos-count">
              📷 {{ funcion.imagenes.length }}
            </div>
          </div>

          <!-- Contenido -->
          <div class="funcion-content">
            <h3 class="funcion-title">{{ funcion.nombre }}</h3>
            <p v-if="funcion.artista" class="funcion-artist">🎤 {{ funcion.artista }}</p>
            <p v-if="funcion.descripcion" class="funcion-description">
              {{ truncateText(funcion.descripcion, 100) }}
            </p>

            <div class="funcion-details">
              <div class="detail-row">
                <span class="detail-icon">📅</span>
                <span class="detail-text">{{ formatDate(funcion.fecha) }}</span>
              </div>
              <div class="detail-row">
                <span class="detail-icon">🕐</span>
                <span class="detail-text">{{ funcion.horario }}</span>
              </div>
              <div class="detail-row">
                <span class="detail-icon">💰</span>
                <span class="detail-text">Desde Bs. {{ funcion.precio_entrada || 40 }}</span>
              </div>
            </div>

            <div class="funcion-actions">
              <router-link 
                v-if="funcion.estado === 'activo'"
                :to="`/funcion/${funcion.id}`" 
                class="btn-details"
              >
                Ver Detalles
              </router-link>
              <router-link 
                v-if="funcion.estado === 'activo'"
                :to="`/reservar/${funcion.id}`" 
                class="btn-reservar"
              >
                Reservar
              </router-link>
              <button v-else class="btn-disabled" disabled>
                No Disponible
              </button>
            </div>
          </div>
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
const loading = ref(true);

const loadFunciones = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/funciones');
    funciones.value = response.data.filter(f => f.estado === 'activo');
  } catch (error) {
    console.error('Error al cargar funciones:', error);
  } finally {
    loading.value = false;
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

const truncateText = (text, maxLength) => {
  if (!text) return '';
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
};

onMounted(() => {
  loadFunciones();
});
</script>

<style scoped>
.funciones-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.navbar {
  background: #1a1a1a;
  padding: 1rem 0;
  color: white;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
  border-radius: 4px;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}

.logo:hover {
  color: #667eea;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-links a {
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  transition: all 0.3s;
}

.nav-links a:hover {
  background: rgba(255,255,255,0.1);
}

.page-header {
  text-align: center;
  padding: 3rem 0 2rem;
}

.page-header h1 {
  font-size: 2.5rem;
  color: #1a1a1a;
  margin-bottom: 0.5rem;
  font-weight: 800;
}

.subtitle {
  font-size: 1.2rem;
  color: #666;
  font-weight: 500;
}

.loading {
  text-align: center;
  padding: 4rem 0;
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

.no-funciones {
  text-align: center;
  padding: 4rem 2rem;
  background: white;
  border-radius: 16px;
  margin: 2rem 0;
}

.no-funciones .icon {
  font-size: 4rem;
  display: block;
  margin-bottom: 1rem;
}

.no-funciones h2 {
  color: #1a1a1a;
  margin-bottom: 0.5rem;
}

.no-funciones p {
  color: #666;
}

.funciones-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
  padding: 2rem 0 4rem;
}

.funcion-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: all 0.3s;
  display: flex;
  flex-direction: column;
}

.funcion-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.funcion-image {
  position: relative;
  width: 100%;
  height: 220px;
  overflow: hidden;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.funcion-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.funcion-card:hover .funcion-image img {
  transform: scale(1.05);
}

.funcion-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
  padding: 1rem;
}

.placeholder-icon {
  font-size: 4rem;
  margin-bottom: 0.5rem;
}

.funcion-placeholder p {
  font-size: 1.2rem;
  font-weight: 600;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.genre-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
}

.status-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(231, 76, 60, 0.9);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 600;
}

.photos-count {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 16px;
  font-size: 0.875rem;
  backdrop-filter: blur(10px);
}

.funcion-content {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.funcion-title {
  font-size: 1.5rem;
  color: #1a1a1a;
  margin-bottom: 0.5rem;
  font-weight: 700;
}

.funcion-artist {
  color: #667eea;
  font-weight: 600;
  margin-bottom: 0.75rem;
  font-size: 1.1rem;
}

.funcion-description {
  color: #666;
  line-height: 1.6;
  margin-bottom: 1rem;
  flex: 1;
}

.funcion-details {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1rem;
}

.detail-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
}

.detail-row:last-child {
  margin-bottom: 0;
}

.detail-icon {
  font-size: 1.2rem;
}

.detail-text {
  color: #495057;
  font-weight: 500;
}

.funcion-actions {
  display: flex;
  gap: 0.75rem;
}

.btn-details,
.btn-reservar,
.btn-disabled {
  flex: 1;
  padding: 0.875rem;
  border-radius: 8px;
  text-align: center;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s;
  border: none;
  cursor: pointer;
}

.btn-details {
  background: #f8f9fa;
  color: #495057;
  border: 2px solid #dee2e6;
}

.btn-details:hover {
  background: #e9ecef;
  border-color: #adb5bd;
}

.btn-reservar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-reservar:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-disabled {
  background: #e9ecef;
  color: #adb5bd;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .page-header h1 {
    font-size: 2rem;
  }

  .funciones-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .funcion-actions {
    flex-direction: column;
  }
}
</style>
