<template>
  <div class="home">
    <!-- Navbar -->
    <nav class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <img src="/images/MerakiLogo.jpg" alt="MERAKI Logo" class="navbar-logo" />
          <h1 class="logo">MERAKI Teatro Bar</h1>
        </div>
        <div class="nav-links">
          <a href="#" @click.prevent="scrollToTop" class="nav-link">Inicio</a>
          <a href="#funciones" class="nav-link">Funciones</a>
          <a href="#menu" class="nav-link">Menú</a>
          <a href="#contacto" class="nav-link">Contacto</a>
          <router-link v-if="!authStore.isAuthenticated" to="/login" class="nav-link">Iniciar Sesión</router-link>
          <router-link v-if="authStore.isAuthenticated" to="/mis-reservas" class="nav-link">Mis Reservas</router-link>
          <router-link v-if="authStore.hasRole(['admin', 'encargado'])" to="/admin" class="btn-admin">Admin</router-link>
          <button v-if="authStore.isAuthenticated" @click="logout" class="btn-logout">Salir</button>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h2 class="hero-title">Bienvenido a MERAKI</h2>
        <p class="hero-subtitle">Donde el arte cobra vida</p>
        <p class="hero-description">
          Vive experiencias únicas con música en vivo, teatro y entretenimiento 
          en un ambiente íntimo y acogedor
        </p>
        <div class="hero-buttons">
          <a href="#funciones" class="btn-primary">Ver Funciones</a>
          <router-link to="/funciones" class="btn-secondary">Reservar Ahora</router-link>
        </div>
      </div>
      <div class="scroll-indicator">
        <span>↓</span>
      </div>
    </section>

    <!-- Próximas Funciones -->
    <section id="funciones" class="funciones-section">
      <div class="container">
        <h2 class="section-title">Próximas Funciones</h2>
        <p class="section-subtitle">No te pierdas nuestros próximos shows</p>
        
        <div v-if="loading" class="loading">
          <p>Cargando funciones...</p>
        </div>
        
        <div v-else-if="funciones.length === 0" class="no-funciones">
          <p>No hay funciones programadas en este momento</p>
          <p class="text-muted">Vuelve pronto para ver nuestros próximos eventos</p>
        </div>
        
        <div v-else class="funciones-grid">
          <div 
            v-for="funcion in funciones" 
            :key="funcion.id"
            class="funcion-card"
          >
            <div class="funcion-image">
              <img 
                v-if="funcion.imagen_principal" 
                :src="`/storage/${funcion.imagen_principal}`" 
                :alt="funcion.nombre"
              />
              <div v-else class="funcion-placeholder">
                <span>🎬</span>
              </div>
              <div class="funcion-badge">{{ funcion.genero }}</div>
            </div>
            <div class="funcion-content">
              <h3 class="funcion-title">{{ funcion.nombre }}</h3>
              <p class="funcion-description">{{ funcion.descripcion }}</p>
              <div class="funcion-details">
                <div class="detail-item">
                  <span class="icon">📅</span>
                  <span>{{ formatDate(funcion.fecha) }}</span>
                </div>
                <div class="detail-item">
                  <span class="icon">🕐</span>
                  <span>{{ funcion.horario }}</span>
                </div>
                <div class="detail-item">
                  <span class="icon">💰</span>
                  <span>Desde Bs. 40</span>
                </div>
              </div>
              <router-link 
                :to="`/reservar/${funcion.id}`" 
                class="btn-reservar"
              >
                Reservar Mesa
              </router-link>
            </div>
          </div>
        </div>
        
        <div class="ver-todas">
          <router-link to="/funciones" class="btn-outline">Ver Todas las Funciones</router-link>
        </div>
      </div>
    </section>

    <!-- Mapa Interactivo Preview -->
    <section class="mapa-section">
      <div class="container">
        <div class="mapa-content">
          <div class="mapa-info">
            <h2 class="section-title">Elige tu Lugar Perfecto</h2>
            <p class="mapa-description">
              Nuestro sistema de reservas te permite seleccionar exactamente dónde quieres sentarte. 
              Visualiza el espacio, elige tu mesa o silla individual, y personaliza tu experiencia.
            </p>
            <ul class="mapa-features">
              <li>✓ Mapa interactivo del local</li>
              <li>✓ Selección de sillas individuales</li>
              <li>✓ Opciones Normal y VIP</li>
              <li>✓ Vista en tiempo real de disponibilidad</li>
            </ul>
            <router-link to="/funciones" class="btn-primary">Explorar Mapa</router-link>
          </div>
          <div class="mapa-preview">
            <div class="preview-box">
              <div class="preview-stage">ESCENARIO</div>
              <div class="preview-tables">
                <div class="preview-table"></div>
                <div class="preview-table"></div>
                <div class="preview-table"></div>
                <div class="preview-table"></div>
                <div class="preview-table"></div>
                <div class="preview-table"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Menú Section -->
    <section id="menu" class="menu-section">
      <div class="container">
        <h2 class="section-title">Nuestro Menú</h2>
        <p class="section-subtitle">Disfruta de nuestra selección de bebidas y comidas</p>
        
        <div class="menu-actions">
          <a href="/menu.pdf" target="_blank" class="btn-menu btn-download" download>
            <span class="icon">📥</span>
            <span class="btn-text">Descargar Menú (PDF)</span>
          </a>
          <a href="/menu.pdf" target="_blank" class="btn-menu btn-view">
            <span class="icon">👁️</span>
            <span class="btn-text">Ver Menú Online</span>
          </a>
        </div>
        
        <div class="menu-preview">
          <div class="menu-category">
            <h3>🍸 Bebidas</h3>
            <p>Cócteles, vinos, cervezas y más</p>
          </div>
          <div class="menu-category">
            <h3>🍴 Comidas</h3>
            <p>Platos especiales y picadas</p>
          </div>
          <div class="menu-category">
            <h3>✨ VIP</h3>
            <p>Trago de cortesía incluido</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Precios Section -->
    <section class="precios-section">
      <div class="container">
        <h2 class="section-title">Precios</h2>
        <div class="precios-grid">
          <div class="precio-card">
            <div class="precio-header">
              <h3>Normal</h3>
            </div>
            <div class="precio-amount">
              <span class="currency">Bs.</span>
              <span class="value">40</span>
            </div>
            <ul class="precio-features">
              <li>✓ Entrada al show</li>
              <li>✓ Selección de asiento</li>
              <li>✓ Ambiente único</li>
            </ul>
          </div>
          
          <div class="precio-card featured">
            <div class="precio-badge">Recomendado</div>
            <div class="precio-header">
              <h3>VIP</h3>
            </div>
            <div class="precio-amount">
              <span class="currency">Bs.</span>
              <span class="value">50</span>
            </div>
            <ul class="precio-features">
              <li>✓ Entrada al show</li>
              <li>✓ Trago de cortesía</li>
              <li>✓ Selección de asiento</li>
              <li>✓ Experiencia premium</li>
            </ul>
          </div>
          
          <div class="precio-card">
            <div class="precio-header">
              <h3>Cena</h3>
            </div>
            <div class="precio-amount">
              <span class="currency">+Bs.</span>
              <span class="value">30</span>
            </div>
            <ul class="precio-features">
              <li>✓ Adicional a tu entrada</li>
              <li>✓ Plato especial</li>
              <li>✓ Disponible Normal y VIP</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto" class="contacto-section">
      <div class="container">
        <h2 class="section-title">Contáctanos</h2>
        <div class="contacto-grid">
          <div class="contacto-info">
            <div class="contacto-item">
              <span class="contacto-icon">📍</span>
              <div>
                <h4>Ubicación</h4>
                <p>Santa Cruz, Bolivia</p>
              </div>
            </div>
            <div class="contacto-item">
              <span class="contacto-icon">📞</span>
              <div>
                <h4>Teléfono</h4>
                <p>+591 77055939</p>
              </div>
            </div>
            <div class="contacto-item">
              <span class="contacto-icon">📧</span>
              <div>
                <h4>Email</h4>
                <p>merakiteatrobar@gmail.com</p>
              </div>
            </div>
            <div class="contacto-item">
              <span class="contacto-icon">🕐</span>
              <div>
                <h4>Horarios</h4>
                <p>Consulta nuestras funciones</p>
              </div>
            </div>
          </div>
          <div class="contacto-social">
            <h3>Síguenos</h3>
            <div class="social-links">
              <a href="#" class="social-link">📘 Facebook</a>
              <a href="#" class="social-link">📷 Instagram</a>
              <a href="#" class="social-link">🐦 Twitter</a>
              <a href="#" class="social-link">📱 WhatsApp</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-brand">
            <img src="/images/MerakiLogo.jpg" alt="MERAKI Logo" class="footer-logo" />
            <h3>MERAKI Teatro Bar</h3>
            <p>Donde el arte cobra vida</p>
          </div>
          <div class="footer-links">
            <h4>Enlaces</h4>
            <a href="#funciones">Funciones</a>
            <a href="#menu">Menú</a>
            <router-link to="/funciones">Reservar</router-link>
            <a href="#contacto">Contacto</a>
          </div>
          <div class="footer-legal">
            <h4>Legal</h4>
            <a href="#">Términos y Condiciones</a>
            <a href="#">Política de Privacidad</a>
            <a href="#">Política de Cancelación</a>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 MERAKI Teatro Bar. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';
import axios from 'axios';

const authStore = useAuthStore();
const router = useRouter();

const funciones = ref([]);
const loading = ref(true);

const loadFunciones = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/funciones');
    // Mostrar solo las próximas 3 funciones
    funciones.value = response.data.slice(0, 3);
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

const logout = async () => {
  await authStore.logout();
  router.push('/');
};

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
};

onMounted(() => {
  loadFunciones();
});
</script>

<style scoped>
/* Variables */
:root {
  --primary: #667eea;
  --secondary: #764ba2;
  --accent: #f093fb;
  --dark: #1a1a2e;
  --light: #f8f9fa;
}

/* Reset */
* {
  scroll-behavior: smooth;
}

.home {
  background: var(--light);
}

/* Navbar */
.navbar {
  background: rgba(26, 26, 46, 0.95);
  backdrop-filter: blur(10px);
  padding: 1rem 0;
  color: white;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 20px rgba(0,0,0,0.1);
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
  height: 45px;
  width: auto;
  object-fit: contain;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-link {
  color: white;
  text-decoration: none;
  transition: all 0.3s;
  font-weight: 500;
}

.nav-link:hover {
  color: var(--accent);
  transform: translateY(-2px);
}

.btn-admin {
  background: var(--primary);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  text-decoration: none;
  transition: all 0.3s;
}

.btn-admin:hover {
  background: var(--secondary);
  transform: translateY(-2px);
}

.btn-logout {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s;
}

.btn-logout:hover {
  background: #c0392b;
  transform: translateY(-2px);
}

/* Hero Section */
.hero {
  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
  min-height: 90vh;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
  animation: pulse 8s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 0.5; }
  50% { opacity: 1; }
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.45);
}

.hero-content {
  position: relative;
  z-index: 1;
  max-width: 800px;
  padding: 2rem;
}

.hero-title {
  font-size: 4rem;
  margin-bottom: 1rem;
  font-weight: 800;
  text-shadow: 0 4px 20px rgba(0,0,0,0.6), 0 2px 10px rgba(0,0,0,0.8);
  animation: fadeInUp 1s ease-out;
}

.hero-subtitle {
  font-size: 2rem;
  margin-bottom: 1rem;
  font-weight: 300;
  font-style: italic;
  animation: fadeInUp 1s ease-out 0.2s both;
  text-shadow: 0 2px 15px rgba(0,0,0,0.6), 0 1px 8px rgba(0,0,0,0.8);
}

.hero-description {
  font-size: 1.2rem;
  margin-bottom: 2rem;
  line-height: 1.6;
  opacity: 0.95;
  animation: fadeInUp 1s ease-out 0.4s both;
  text-shadow: 0 2px 10px rgba(0,0,0,0.6), 0 1px 5px rgba(0,0,0,0.8);
  font-weight: 500;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hero-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  animation: fadeInUp 1s ease-out 0.6s both;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 1rem 2.5rem;
  border-radius: 50px;
  text-decoration: none;
  font-weight: bold;
  font-size: 1.1rem;
  display: inline-block;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.4);
  border: 3px solid white;
  text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.btn-primary:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 6px 25px rgba(0,0,0,0.5);
  filter: brightness(1.1);
}

.btn-secondary {
  background: white;
  color: #667eea;
  padding: 1rem 2.5rem;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 800;
  font-size: 1.1rem;
  display: inline-block;
  transition: all 0.3s;
  border: 3px solid white;
  box-shadow: 0 4px 15px rgba(0,0,0,0.4);
}

.btn-secondary:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 6px 20px rgba(0,0,0,0.5);
  background: #f8f9fa;
  color: #667eea;
}

.scroll-indicator {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  animation: bounce 2s infinite;
}

.scroll-indicator span {
  font-size: 2rem;
  color: white;
  opacity: 0.7;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
  40% { transform: translateX(-50%) translateY(-10px); }
  60% { transform: translateX(-50%) translateY(-5px); }
}

/* Sections */
section {
  padding: 5rem 0;
}

.section-title {
  font-size: 2.5rem;
  text-align: center;
  margin-bottom: 1rem;
  color: #1a1a2e;
  font-weight: 800;
}

.section-subtitle {
  text-align: center;
  font-size: 1.2rem;
  color: #1a1a2e;
  margin-bottom: 3rem;
  font-weight: 600;
}

/* Funciones Section */
.funciones-section {
  background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 100%);
}

.funciones-section .section-title,
.funciones-section .section-subtitle {
  color: white;
  text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.funciones-section .loading,
.funciones-section .no-funciones {
  color: white;
}

.loading, .no-funciones {
  text-align: center;
  padding: 3rem;
  color: #1a1a2e;
  font-weight: 600;
}

.text-muted {
  color: #555;
  font-size: 0.9rem;
  font-weight: 500;
}

.funciones-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.funcion-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: all 0.3s;
}

.funcion-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.funcion-image {
  position: relative;
  height: 200px;
  overflow: hidden;
  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
}

.funcion-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.funcion-card:hover .funcion-image img {
  transform: scale(1.1);
}

.funcion-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 4rem;
}

.funcion-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(255,255,255,0.95);
  color: var(--primary);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.funcion-content {
  padding: 1.5rem;
}

.funcion-title {
  font-size: 1.5rem;
  margin-bottom: 0.75rem;
  color: #1a1a2e;
  font-weight: 800;
}

.funcion-description {
  color: #1a1a2e;
  margin-bottom: 1rem;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-weight: 500;
}

.funcion-details {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #1a1a2e;
  font-size: 0.9rem;
  font-weight: 600;
}

.detail-item .icon {
  font-size: 1.1rem;
}

.btn-reservar {
  display: block;
  width: 100%;
  background: var(--primary);
  color: white;
  text-align: center;
  padding: 0.75rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-reservar:hover {
  background: var(--secondary);
  transform: translateY(-2px);
}

.ver-todas {
  text-align: center;
  margin-top: 2rem;
}

.btn-outline {
  display: inline-block;
  padding: 1rem 2.5rem;
  border: 3px solid white;
  color: white;
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border-radius: 50px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.btn-outline:hover {
  background: white;
  color: #1a1a2e;
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 6px 25px rgba(255,255,255,0.4);
}

/* Mapa Section */
.mapa-section {
  background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 100%);
}

.mapa-section .section-title {
  color: white;
  text-shadow: 0 3px 15px rgba(0,0,0,0.8), 0 1px 5px rgba(0,0,0,1);
}

.mapa-description {
  font-size: 1.1rem;
  line-height: 1.8;
  color: white;
  margin-bottom: 2rem;
  font-weight: 600;
  text-shadow: 0 2px 10px rgba(0,0,0,0.8), 0 1px 5px rgba(0,0,0,1);
}

.mapa-features li {
  padding: 0.75rem 0;
  font-size: 1.1rem;
  color: white;
  font-weight: 600;
  text-shadow: 0 2px 8px rgba(0,0,0,0.8), 0 1px 4px rgba(0,0,0,1);
}

.mapa-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
}

.mapa-info h2 {
  text-align: left;
  margin-bottom: 1.5rem;
  color: white;
  font-weight: 800;
  text-shadow: 0 3px 15px rgba(0,0,0,0.8), 0 1px 5px rgba(0,0,0,1);
}

.mapa-description {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #1a1a2e;
  margin-bottom: 2rem;
  font-weight: 600;
}

.mapa-features {
  list-style: none;
  padding: 0;
  margin-bottom: 2rem;
}

.mapa-features li {
  padding: 0.75rem 0;
  font-size: 1.1rem;
  color: #1a1a2e;
  font-weight: 600;
}

.mapa-preview {
  display: flex;
  justify-content: center;
  align-items: center;
}

.preview-box {
  background: rgba(26, 26, 46, 0.9);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  width: 100%;
  max-width: 400px;
}

.preview-stage {
  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
  color: white;
  text-align: center;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  font-weight: 700;
  letter-spacing: 2px;
}

.preview-tables {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
}

.preview-table {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #8b4513 0%, #654321 100%);
  border-radius: 50%;
  border: 3px solid #27ae60;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  animation: tableFloat 3s ease-in-out infinite;
}

.preview-table:nth-child(1) { animation-delay: 0s; }
.preview-table:nth-child(2) { animation-delay: 0.2s; }
.preview-table:nth-child(3) { animation-delay: 0.4s; }
.preview-table:nth-child(4) { animation-delay: 0.6s; }
.preview-table:nth-child(5) { animation-delay: 0.8s; }
.preview-table:nth-child(6) { animation-delay: 1s; }

@keyframes tableFloat {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* Menu Section */
.menu-section {
  background: white;
}

.menu-actions {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  margin-bottom: 3rem;
  flex-wrap: wrap;
}

.btn-menu {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1.25rem 2.5rem;
  background: white;
  color: #1a1a2e;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  border: 3px solid transparent;
}

.btn-menu:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.btn-download {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: white;
}

.btn-download:hover {
  filter: brightness(1.1);
}

.btn-view {
  background: white;
  color: #667eea;
  border-color: #667eea;
}

.btn-view:hover {
  background: #667eea;
  color: white;
}

.btn-menu .icon {
  font-size: 1.5rem;
}

.btn-text {
  font-weight: 700;
}

.menu-preview {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
}

.menu-category {
  background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
  padding: 2rem;
  border-radius: 16px;
  text-align: center;
  transition: all 0.3s;
  border: 2px solid rgba(102, 126, 234, 0.2);
}

.menu-category:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.menu-category h3 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  color: #1a1a2e;
  font-weight: 800;
}

.menu-category p {
  color: #1a1a2e;
  font-size: 1.1rem;
  font-weight: 600;
}

/* Precios Section */
.precios-section {
  background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 100%);
  color: white;
}

.precios-section .section-title {
  color: white;
  text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.precios-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  max-width: 1000px;
  margin: 0 auto;
}

.precio-card {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 2rem;
  text-align: center;
  border: 2px solid rgba(102, 126, 234, 0.3);
  transition: all 0.3s;
  position: relative;
  color: #1a1a2e;
}

.precio-card:hover {
  transform: translateY(-10px);
  border-color: var(--primary);
  box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
}

.precio-card.featured {
  border-color: var(--primary);
  background: rgba(102, 126, 234, 0.95);
  transform: scale(1.05);
  color: white;
}

.precio-badge {
  position: absolute;
  top: -15px;
  left: 50%;
  transform: translateX(-50%);
  background: #1a1a2e;
  color: white;
  padding: 0.5rem 1.5rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
  border: 2px solid white;
}

.precio-header h3 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
  font-weight: 800;
}

.precio-amount {
  margin: 2rem 0;
}

.currency {
  font-size: 1.5rem;
  vertical-align: top;
  font-weight: 700;
}

.value {
  font-size: 4rem;
  font-weight: 800;
}

.precio-features {
  list-style: none;
  padding: 0;
  margin: 2rem 0;
}

.precio-features li {
  padding: 0.75rem 0;
  border-bottom: 1px solid rgba(0,0,0,0.1);
  font-size: 1.05rem;
  font-weight: 500;
}

.precio-card.featured .precio-features li {
  border-bottom: 1px solid rgba(255,255,255,0.2);
}

.precio-features li:last-child {
  border-bottom: none;
}

/* Contacto Section */
.contacto-section {
  background: white;
}

.contacto-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
}

.contacto-info {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.contacto-item {
  display: flex;
  gap: 1.5rem;
  align-items: flex-start;
}

.contacto-icon {
  font-size: 2rem;
  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  flex-shrink: 0;
}

.contacto-item h4 {
  margin-bottom: 0.5rem;
  color: #1a1a2e;
  font-size: 1.2rem;
  font-weight: 800;
}

.contacto-item p {
  color: #1a1a2e;
  font-size: 1.05rem;
  font-weight: 600;
}

.contacto-social h3 {
  margin-bottom: 1.5rem;
  color: #1a1a2e;
  font-size: 1.8rem;
  font-weight: 800;
}

.social-links {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.social-link {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
  border-radius: 12px;
  text-decoration: none;
  color: #1a1a2e;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s;
  border: 2px solid rgba(102, 126, 234, 0.2);
}

.social-link:hover {
  transform: translateX(10px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Footer */
.footer {
  background: #0f0f1e;
  color: white;
  padding: 3rem 0 1rem;
}

.footer-content {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr;
  gap: 3rem;
  margin-bottom: 2rem;
}

.footer-brand {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.footer-logo {
  height: 60px;
  width: auto;
  object-fit: contain;
}

.footer-brand h3 {
  font-size: 1.5rem;
  margin: 0;
  color: white;
  font-weight: 800;
}

.footer-brand p {
  color: rgba(255,255,255,0.9);
  font-style: italic;
  font-weight: 500;
}

.footer-links, .footer-legal {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.footer-links h4, .footer-legal h4 {
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
  color: white;
  font-weight: 700;
}

.footer-links a, .footer-legal a {
  color: rgba(255,255,255,0.9);
  text-decoration: none;
  transition: color 0.3s;
  font-weight: 500;
}

.footer-links a:hover, .footer-legal a:hover {
  color: white;
}

.footer-bottom {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid rgba(255,255,255,0.2);
  color: rgba(255,255,255,0.8);
  font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.5rem;
  }
  
  .hero-description {
    font-size: 1rem;
  }
  
  .hero-buttons {
    flex-direction: column;
  }
  
  .nav-links {
    flex-wrap: wrap;
    gap: 0.75rem;
    font-size: 0.9rem;
  }
  
  .mapa-content,
  .contacto-grid,
  .footer-content {
    grid-template-columns: 1fr;
  }
  
  .funciones-grid {
    grid-template-columns: 1fr;
  }
  
  section {
    padding: 3rem 0;
  }
  
  .section-title {
    font-size: 2rem;
  }
}
</style>
