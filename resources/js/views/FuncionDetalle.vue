<template>
  <div class="funcion-detalle-page">
    <nav class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <img src="/images/MerakiLogo.jpg" alt="MERAKI Logo" class="navbar-logo" />
          <router-link to="/" class="logo">MERAKI</router-link>
        </div>
        <div class="nav-links">
          <router-link to="/funciones">← Volver a Funciones</router-link>
        </div>
      </div>
    </nav>

    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Cargando...</p>
    </div>

    <div v-else-if="funcion" class="container">
      <!-- Hero con imagen principal -->
      <div class="hero-section">
        <div class="hero-image">
          <img 
            v-if="funcion.imagen_principal" 
            :src="`/storage/${funcion.imagen_principal.ruta}`" 
            :alt="funcion.imagen_principal.alt_text || funcion.nombre"
          />
          <div v-else class="hero-placeholder">
            <span>🎭</span>
            <h2>{{ funcion.nombre }}</h2>
          </div>
          <div class="hero-overlay"></div>
          <div class="hero-content">
            <h1>{{ funcion.nombre }}</h1>
            <p v-if="funcion.artista" class="artist">🎤 {{ funcion.artista }}</p>
            <div class="hero-badges">
              <span v-if="funcion.genero_musical" class="badge">{{ funcion.genero_musical }}</span>
              <span class="badge">{{ formatDate(funcion.fecha) }}</span>
              <span class="badge">{{ funcion.horario }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Información y Galería -->
      <div class="content-grid">
        <!-- Columna Izquierda: Info -->
        <div class="info-section">
          <div class="info-card">
            <h2>📋 Detalles del Evento</h2>
            <div class="info-item">
              <span class="label">Fecha:</span>
              <span class="value">{{ formatDateLong(funcion.fecha) }}</span>
            </div>
            <div class="info-item">
              <span class="label">Horario:</span>
              <span class="value">{{ funcion.horario }}</span>
            </div>
            <div v-if="funcion.genero_musical" class="info-item">
              <span class="label">Género:</span>
              <span class="value">{{ funcion.genero_musical }}</span>
            </div>
            <div class="info-item">
              <span class="label">Entrada Normal:</span>
              <span class="value">Bs. {{ funcion.precio_entrada || 40 }}</span>
            </div>
            <div class="info-item">
              <span class="label">Entrada VIP:</span>
              <span class="value">Bs. {{ funcion.precio_vip || 50 }}</span>
            </div>
            <div class="info-item">
              <span class="label">Cena (opcional):</span>
              <span class="value">+Bs. {{ funcion.precio_entrada_cena || 30 }}</span>
            </div>
          </div>

          <div v-if="funcion.descripcion" class="info-card">
            <h2>📝 Descripción</h2>
            <p class="description">{{ funcion.descripcion }}</p>
          </div>

          <router-link 
            v-if="funcion.estado === 'activo'"
            :to="`/reservar/${funcion.id}`" 
            class="btn-reservar-main"
          >
            🎫 Reservar Ahora
          </router-link>
        </div>

        <!-- Columna Derecha: Galería -->
        <div class="gallery-section">
          <h2>📷 Galería de Fotos</h2>
          
          <div v-if="funcion.imagenes && funcion.imagenes.length > 0" class="gallery">
            <!-- Imagen Principal Grande -->
            <div class="main-image">
              <img 
                :src="`/storage/${currentImage.ruta}`" 
                :alt="currentImage.alt_text"
                @click="openLightbox(currentImageIndex)"
              />
              <div class="image-counter">
                {{ currentImageIndex + 1 }} / {{ funcion.imagenes.length }}
              </div>
              <button 
                v-if="funcion.imagenes.length > 1"
                @click="prevImage" 
                class="nav-btn prev-btn"
              >
                ‹
              </button>
              <button 
                v-if="funcion.imagenes.length > 1"
                @click="nextImage" 
                class="nav-btn next-btn"
              >
                ›
              </button>
            </div>

            <!-- Miniaturas -->
            <div v-if="funcion.imagenes.length > 1" class="thumbnails">
              <div 
                v-for="(imagen, index) in funcion.imagenes" 
                :key="imagen.id"
                :class="['thumbnail', { active: index === currentImageIndex }]"
                @click="currentImageIndex = index"
              >
                <img 
                  :src="`/storage/${imagen.ruta}`" 
                  :alt="imagen.alt_text"
                />
              </div>
            </div>
          </div>

          <div v-else class="no-gallery">
            <span class="icon">📸</span>
            <p>No hay fotos disponibles</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Lightbox -->
    <div v-if="showLightbox" class="lightbox" @click="closeLightbox">
      <div class="lightbox-content" @click.stop>
        <button @click="closeLightbox" class="close-btn">✕</button>
        <img 
          :src="`/storage/${funcion.imagenes[lightboxIndex].ruta}`" 
          :alt="funcion.imagenes[lightboxIndex].alt_text"
        />
        <button 
          v-if="funcion.imagenes.length > 1"
          @click="prevLightbox" 
          class="lightbox-nav prev"
        >
          ‹
        </button>
        <button 
          v-if="funcion.imagenes.length > 1"
          @click="nextLightbox" 
          class="lightbox-nav next"
        >
          ›
        </button>
        <div class="lightbox-counter">
          {{ lightboxIndex + 1 }} / {{ funcion.imagenes.length }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const funcion = ref(null);
const loading = ref(true);
const currentImageIndex = ref(0);
const showLightbox = ref(false);
const lightboxIndex = ref(0);

const currentImage = computed(() => {
  if (!funcion.value?.imagenes?.length) return null;
  return funcion.value.imagenes[currentImageIndex.value];
});

const loadFuncion = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`/api/funciones/${route.params.id}`);
    funcion.value = response.data;
  } catch (error) {
    console.error('Error cargando función:', error);
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-BO', {
    day: 'numeric',
    month: 'short',
  });
};

const formatDateLong = (date) => {
  return new Date(date).toLocaleDateString('es-BO', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const nextImage = () => {
  if (currentImageIndex.value < funcion.value.imagenes.length - 1) {
    currentImageIndex.value++;
  } else {
    currentImageIndex.value = 0;
  }
};

const prevImage = () => {
  if (currentImageIndex.value > 0) {
    currentImageIndex.value--;
  } else {
    currentImageIndex.value = funcion.value.imagenes.length - 1;
  }
};

const openLightbox = (index) => {
  lightboxIndex.value = index;
  showLightbox.value = true;
};

const closeLightbox = () => {
  showLightbox.value = false;
};

const nextLightbox = () => {
  if (lightboxIndex.value < funcion.value.imagenes.length - 1) {
    lightboxIndex.value++;
  } else {
    lightboxIndex.value = 0;
  }
};

const prevLightbox = () => {
  if (lightboxIndex.value > 0) {
    lightboxIndex.value--;
  } else {
    lightboxIndex.value = funcion.value.imagenes.length - 1;
  }
};

onMounted(() => {
  loadFuncion();
});
</script>

<style scoped>
.funcion-detalle-page {
  min-height: 100vh;
  background: #f5f7fa;
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
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
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

.hero-section {
  margin-bottom: 2rem;
}

.hero-image {
  position: relative;
  width: 100%;
  height: 400px;
  border-radius: 16px;
  overflow: hidden;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hero-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hero-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: white;
}

.hero-placeholder span {
  font-size: 5rem;
  margin-bottom: 1rem;
}

.hero-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 60%;
  background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
}

.hero-content {
  position: absolute;
  bottom: 2rem;
  left: 2rem;
  right: 2rem;
  color: white;
  z-index: 1;
}

.hero-content h1 {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.artist {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

.hero-badges {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.badge {
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
}

.content-grid {
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 2rem;
  padding-bottom: 4rem;
}

.info-card {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.info-card h2 {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #1a1a1a;
}

.info-item {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
  border-bottom: none;
}

.label {
  font-weight: 600;
  color: #495057;
}

.value {
  color: #667eea;
  font-weight: 600;
}

.description {
  line-height: 1.8;
  color: #495057;
}

.btn-reservar-main {
  display: block;
  width: 100%;
  padding: 1.25rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-align: center;
  text-decoration: none;
  border-radius: 12px;
  font-size: 1.2rem;
  font-weight: 700;
  transition: all 0.3s;
}

.btn-reservar-main:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.gallery-section {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.gallery-section h2 {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #1a1a1a;
}

.main-image {
  position: relative;
  width: 100%;
  height: 400px;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 1rem;
  cursor: pointer;
}

.main-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.main-image:hover img {
  transform: scale(1.05);
}

.image-counter {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
  backdrop-filter: blur(10px);
}

.nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.7);
  color: white;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 2rem;
  cursor: pointer;
  transition: all 0.3s;
  backdrop-filter: blur(10px);
}

.nav-btn:hover {
  background: rgba(0,0,0,0.9);
}

.prev-btn {
  left: 1rem;
}

.next-btn {
  right: 1rem;
}

.thumbnails {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 0.75rem;
}

.thumbnail {
  height: 80px;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  border: 3px solid transparent;
  transition: all 0.3s;
}

.thumbnail:hover {
  border-color: #667eea;
}

.thumbnail.active {
  border-color: #667eea;
  box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
}

.thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.no-gallery {
  text-align: center;
  padding: 3rem;
  color: #adb5bd;
}

.no-gallery .icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 1rem;
}

/* Lightbox */
.lightbox {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.95);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(10px);
}

.lightbox-content {
  position: relative;
  max-width: 90vw;
  max-height: 90vh;
}

.lightbox-content img {
  max-width: 100%;
  max-height: 90vh;
  border-radius: 8px;
}

.close-btn {
  position: absolute;
  top: -50px;
  right: 0;
  background: rgba(255,255,255,0.2);
  color: white;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  transition: all 0.3s;
}

.close-btn:hover {
  background: rgba(255,255,255,0.3);
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.2);
  color: white;
  border: none;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  font-size: 2.5rem;
  cursor: pointer;
  transition: all 0.3s;
}

.lightbox-nav:hover {
  background: rgba(255,255,255,0.3);
}

.lightbox-nav.prev {
  left: -80px;
}

.lightbox-nav.next {
  right: -80px;
}

.lightbox-counter {
  position: absolute;
  bottom: -50px;
  left: 50%;
  transform: translateX(-50%);
  color: white;
  font-size: 1.2rem;
  font-weight: 600;
}

@media (max-width: 968px) {
  .content-grid {
    grid-template-columns: 1fr;
  }

  .hero-content h1 {
    font-size: 2rem;
  }

  .lightbox-nav.prev {
    left: 10px;
  }

  .lightbox-nav.next {
    right: 10px;
  }
}
</style>
