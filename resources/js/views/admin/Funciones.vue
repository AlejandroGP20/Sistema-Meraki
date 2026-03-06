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
      <div class="header">
        <h1>Gestión de Funciones</h1>
        <button @click="showModal = true" class="btn-primary">+ Nueva Función</button>
      </div>

      <table class="data-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Artista</th>
            <th>Fecha</th>
            <th>Horario</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="funcion in funciones" :key="funcion.id">
            <td>{{ funcion.nombre }}</td>
            <td>{{ funcion.artista }}</td>
            <td>{{ formatDate(funcion.fecha) }}</td>
            <td>{{ funcion.horario }}</td>
            <td><span :class="`badge badge-${funcion.estado}`">{{ funcion.estado }}</span></td>
            <td>
              <button @click="editFuncion(funcion)" class="btn-edit">Editar</button>
              <button @click="deleteFuncion(funcion.id)" class="btn-delete">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal -->
      <div v-if="showModal" class="modal">
        <div class="modal-content">
          <h2>{{ editingId ? 'Editar' : 'Nueva' }} Función</h2>
          <form @submit.prevent="saveFuncion">
            <div class="form-group">
              <label>Nombre</label>
              <input v-model="form.nombre" required />
            </div>
            <div class="form-group">
              <label>Artista</label>
              <input v-model="form.artista" />
            </div>
            <div class="form-group">
              <label>Género Musical</label>
              <input v-model="form.genero_musical" />
            </div>
            <div class="form-group">
              <label>Fecha</label>
              <input v-model="form.fecha" type="date" required />
            </div>
            <div class="form-group">
              <label>Horario</label>
              <select v-model="form.horario" required>
                <option value="19:00">19:00</option>
                <option value="21:30">21:30</option>
              </select>
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn-save">Guardar</button>
              <button type="button" @click="closeModal" class="btn-cancel">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const funciones = ref([]);
const showModal = ref(false);
const editingId = ref(null);

const form = ref({
  nombre: '',
  artista: '',
  genero_musical: '',
  fecha: '',
  horario: '19:00',
});

const loadFunciones = async () => {
  const response = await axios.get('/api/funciones');
  funciones.value = response.data;
};

const saveFuncion = async () => {
  try {
    if (editingId.value) {
      await axios.put(`/api/funciones/${editingId.value}`, form.value);
    } else {
      await axios.post('/api/funciones', form.value);
    }
    closeModal();
    loadFunciones();
  } catch (error) {
    alert('Error al guardar');
  }
};

const editFuncion = (funcion) => {
  editingId.value = funcion.id;
  form.value = { ...funcion };
  showModal.value = true;
};

const deleteFuncion = async (id) => {
  if (!confirm('¿Eliminar función?')) return;
  await axios.delete(`/api/funciones/${id}`);
  loadFunciones();
};

const closeModal = () => {
  showModal.value = false;
  editingId.value = null;
  form.value = { nombre: '', artista: '', genero_musical: '', fecha: '', horario: '19:00' };
};

const formatDate = (date) => new Date(date).toLocaleDateString('es-BO');

onMounted(() => {
  loadFunciones();
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

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.btn-primary {
  padding: 0.75rem 1.5rem;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.data-table {
  width: 100%;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

.badge-activo {
  background: #27ae60;
  color: white;
}

.btn-edit, .btn-delete {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 0.5rem;
}

.btn-edit {
  background: #3498db;
  color: white;
}

.btn-delete {
  background: #e74c3c;
  color: white;
}

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
  max-width: 500px;
  width: 100%;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
}

.btn-save, .btn-cancel {
  flex: 1;
  padding: 0.75rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-save {
  background: #27ae60;
  color: white;
}

.btn-cancel {
  background: #95a5a6;
  color: white;
}
</style>
