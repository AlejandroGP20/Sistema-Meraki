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
      <h1>Dashboard</h1>
      
      <div class="stats-grid">
        <div class="stat-card">
          <h3>Reservas Hoy</h3>
          <p class="stat-number">{{ stats.reservasHoy }}</p>
        </div>
        <div class="stat-card">
          <h3>Ingresos del Mes</h3>
          <p class="stat-number">Bs. {{ stats.ingresosMes }}</p>
        </div>
        <div class="stat-card">
          <h3>Funciones Activas</h3>
          <p class="stat-number">{{ stats.funcionesActivas }}</p>
        </div>
        <div class="stat-card">
          <h3>Ocupación Promedio</h3>
          <p class="stat-number">{{ stats.ocupacion }}%</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref({
  reservasHoy: 0,
  ingresosMes: 0,
  funcionesActivas: 0,
  ocupacion: 0,
});

onMounted(async () => {
  try {
    const [reservas, funciones] = await Promise.all([
      axios.get('/api/reservas'),
      axios.get('/api/funciones'),
    ]);
    
    stats.value.reservasHoy = reservas.data.filter(r => {
      const today = new Date().toDateString();
      return new Date(r.created_at).toDateString() === today;
    }).length;
    
    stats.value.funcionesActivas = funciones.data.filter(f => f.estado === 'activo').length;
    
    const thisMonth = reservas.data.filter(r => {
      const date = new Date(r.created_at);
      const now = new Date();
      return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
    });
    
    stats.value.ingresosMes = thisMonth.reduce((sum, r) => sum + parseFloat(r.monto_total), 0);
  } catch (error) {
    console.error('Error al cargar estadísticas:', error);
  }
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
  transition: background 0.3s;
}

.admin-sidebar a:hover,
.admin-sidebar a.router-link-active {
  background: #667eea;
}

.admin-content {
  flex: 1;
  padding: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-card h3 {
  font-size: 0.875rem;
  color: #666;
  margin-bottom: 0.5rem;
}

.stat-number {
  font-size: 2rem;
  font-weight: bold;
  color: #667eea;
}
</style>
