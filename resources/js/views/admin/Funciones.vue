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
        <h1>Gestión de Funciones</h1>
        <button @click="openCreateModal" class="btn-primary">+ Nueva Función</button>
      </div>

      <table class="data-table">
        <thead>
          <tr>
            <th>Portada</th>
            <th>Nombre</th>
            <th>Artista</th>
            <th>Fecha</th>
            <th>Horario</th>
            <th>Fotos</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="funcion in funciones" :key="funcion.id">
            <td>
              <img 
                v-if="funcion.imagen_principal" 
                :src="`/storage/${funcion.imagen_principal.ruta}`" 
                :alt="funcion.imagen_principal.alt_text"
                class="thumbnail"
              />
              <div v-else class="no-image">Sin foto</div>
            </td>
            <td>{{ funcion.nombre }}</td>
            <td>{{ funcion.artista }}</td>
            <td>{{ formatDate(funcion.fecha) }}</td>
            <td>{{ funcion.horario }}</td>
            <td>
              <span class="badge-count">{{ funcion.imagenes?.length || 0 }} fotos</span>
            </td>
            <td><span :class="`badge badge-${funcion.estado}`">{{ funcion.estado }}</span></td>
            <td>
              <button @click="editFuncion(funcion)" class="btn-edit">Editar</button>
              <button @click="managePhotos(funcion)" class="btn-photos">📷 Fotos</button>
              <button @click="deleteFuncion(funcion.id)" class="btn-delete">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal Crear/Editar Función -->
      <div v-if="showModal" class="modal" @click.self="closeModal">
        <div class="modal-content">
          <h2>{{ editingId ? 'Editar' : 'Nueva' }} Función</h2>
          <form @submit.prevent="saveFuncion">
            <div class="form-row">
              <div class="form-group">
                <label>Nombre *</label>
                <input v-model="form.nombre" required />
              </div>
              <div class="form-group">
                <label>Artista</label>
                <input v-model="form.artista" />
              </div>
            </div>

            <div class="form-group">
              <label>Género Musical</label>
              <input v-model="form.genero_musical" placeholder="Rock, Jazz, Stand-up..." />
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Fecha *</label>
                <input v-model="form.fecha" type="date" required />
              </div>
              <div class="form-group">
                <label>Horario *</label>
                <select v-model="form.horario" required>
                  <option value="19:00">19:00</option>
                  <option value="21:30">21:30</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Descripción</label>
              <textarea v-model="form.descripcion" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label>Estado</label>
              <select v-model="form.estado">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>

            <div class="modal-actions">
              <button type="submit" class="btn-save">Guardar</button>
              <button type="button" @click="closeModal" class="btn-cancel">Cancelar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Gestión de Fotos -->
      <div v-if="showPhotosModal" class="modal" @click.self="closePhotosModal">
        <div class="modal-content modal-large">
          <h2>📷 Gestión de Fotos - {{ currentFuncion?.nombre }}</h2>
          
          <!-- Upload Section -->
          <div class="upload-section">
            <label class="upload-label">
              <input 
                type="file" 
                multiple 
                accept="image/*" 
                @change="handleFileUpload"
                ref="fileInput"
                style="display: none"
              />
              <div class="upload-box">
                <span class="upload-icon">📤</span>
                <p>Click para subir fotos</p>
                <small>Múltiples archivos permitidos (JPG, PNG, WEBP - Max 5MB c/u)</small>
              </div>
            </label>
          </div>

          <!-- Preview de fotos a subir -->
          <div v-if="uploadPreviews.length" class="upload-previews">
            <h3>Fotos a subir:</h3>
            <div class="preview-grid">
              <div v-for="(preview, index) in uploadPreviews" :key="index" class="preview-item">
                <img :src="preview.url" :alt="preview.name" />
                <input 
                  v-model="preview.altText" 
                  placeholder="Texto alternativo (SEO)"
                  class="alt-input"
                />
                <button @click="removePreview(index)" class="btn-remove-preview">✕</button>
              </div>
            </div>
            <button @click="uploadPhotos" class="btn-upload" :disabled="uploading">
              {{ uploading ? 'Subiendo...' : 'Subir Fotos' }}
            </button>
          </div>

          <!-- Galería de fotos existentes -->
          <div v-if="currentFuncion?.imagenes?.length" class="photos-gallery">
            <h3>Fotos actuales (Arrastra para reordenar):</h3>
            <draggable 
              v-model="currentFuncion.imagenes" 
              @end="updateOrder"
              item-key="id"
              class="draggable-grid"
            >
              <template #item="{element}">
                <div class="photo-card">
                  <img :src="`/storage/${element.ruta}`" :alt="element.alt_text" />
                  
                  <div class="photo-overlay">
                    <button 
                      @click="setPrincipal(element)" 
                      :class="['btn-star', { active: element.es_principal }]"
                      title="Marcar como portada"
                    >
                      {{ element.es_principal ? '⭐' : '☆' }}
                    </button>
                    <button @click="deletePhoto(element)" class="btn-delete-photo" title="Eliminar">
                      🗑️
                    </button>
                  </div>

                  <div class="photo-info">
                    <input 
                      v-model="element.alt_text" 
                      @blur="updateAltText(element)"
                      placeholder="Texto alternativo"
                      class="alt-input-small"
                    />
                    <span v-if="element.es_principal" class="badge-principal">PORTADA</span>
                  </div>
                </div>
              </template>
            </draggable>
          </div>

          <div v-else class="no-photos">
            <p>📸 No hay fotos aún. ¡Sube algunas!</p>
          </div>

          <div class="modal-actions">
            <button @click="closePhotosModal" class="btn-close">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { VueDraggableNext } from 'vue-draggable-next';

const draggable = VueDraggableNext;

const funciones = ref([]);
const showModal = ref(false);
const showPhotosModal = ref(false);
const editingId = ref(null);
const currentFuncion = ref(null);
const fileInput = ref(null);
const uploadPreviews = ref([]);
const uploading = ref(false);

const form = ref({
  nombre: '',
  artista: '',
  genero_musical: '',
  fecha: '',
  horario: '19:00',
  descripcion: '',
  estado: 'activo',
});

const loadFunciones = async () => {
  try {
    const response = await axios.get('/api/funciones');
    funciones.value = response.data;
  } catch (error) {
    console.error('Error cargando funciones:', error);
  }
};

const openCreateModal = () => {
  editingId.value = null;
  form.value = {
    nombre: '',
    artista: '',
    genero_musical: '',
    fecha: '',
    horario: '19:00',
    descripcion: '',
    estado: 'activo',
  };
  showModal.value = true;
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
    alert('Error al guardar: ' + (error.response?.data?.message || error.message));
  }
};

const editFuncion = (funcion) => {
  editingId.value = funcion.id;
  form.value = { ...funcion };
  showModal.value = true;
};

const deleteFuncion = async (id) => {
  if (!confirm('¿Eliminar función? Esto eliminará también todas sus fotos.')) return;
  try {
    await axios.delete(`/api/funciones/${id}`);
    loadFunciones();
  } catch (error) {
    alert('Error al eliminar');
  }
};

const closeModal = () => {
  showModal.value = false;
  editingId.value = null;
};

// Gestión de fotos
const managePhotos = async (funcion) => {
  try {
    const response = await axios.get(`/api/funciones/${funcion.id}`);
    currentFuncion.value = response.data;
    uploadPreviews.value = [];
    showPhotosModal.value = true;
  } catch (error) {
    alert('Error cargando fotos');
  }
};

const handleFileUpload = (event) => {
  const files = Array.from(event.target.files);
  
  files.forEach(file => {
    if (file.size > 5 * 1024 * 1024) {
      alert(`${file.name} es muy grande (max 5MB)`);
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      uploadPreviews.value.push({
        file,
        url: e.target.result,
        name: file.name,
        altText: currentFuncion.value.nombre,
      });
    };
    reader.readAsDataURL(file);
  });

  event.target.value = '';
};

const removePreview = (index) => {
  uploadPreviews.value.splice(index, 1);
};

const uploadPhotos = async () => {
  if (!uploadPreviews.value.length) return;

  uploading.value = true;
  const formData = new FormData();

  uploadPreviews.value.forEach((preview, index) => {
    formData.append(`imagenes[${index}]`, preview.file);
    formData.append(`alt_texts[${index}]`, preview.altText);
  });

  try {
    await axios.post(`/api/funciones/${currentFuncion.value.id}/imagenes`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    // Recargar fotos
    const response = await axios.get(`/api/funciones/${currentFuncion.value.id}`);
    currentFuncion.value = response.data;
    uploadPreviews.value = [];
    loadFunciones();
  } catch (error) {
    alert('Error subiendo fotos: ' + (error.response?.data?.message || error.message));
  } finally {
    uploading.value = false;
  }
};

const updateOrder = async () => {
  const orden = currentFuncion.value.imagenes.map(img => img.id);
  
  try {
    await axios.post(`/api/funciones/${currentFuncion.value.id}/imagenes/reorder`, { orden });
  } catch (error) {
    console.error('Error actualizando orden:', error);
  }
};

const setPrincipal = async (imagen) => {
  try {
    await axios.post(`/api/funciones/${currentFuncion.value.id}/imagenes/${imagen.id}/principal`);
    
    // Actualizar localmente
    currentFuncion.value.imagenes.forEach(img => {
      img.es_principal = img.id === imagen.id;
    });
    
    loadFunciones();
  } catch (error) {
    alert('Error marcando como principal');
  }
};

const updateAltText = async (imagen) => {
  try {
    await axios.put(`/api/imagenes/${imagen.id}/alt-text`, {
      alt_text: imagen.alt_text
    });
  } catch (error) {
    console.error('Error actualizando alt text:', error);
  }
};

const deletePhoto = async (imagen) => {
  if (!confirm('¿Eliminar esta foto?')) return;

  try {
    await axios.delete(`/api/imagenes/${imagen.id}`);
    
    // Remover localmente
    const index = currentFuncion.value.imagenes.findIndex(img => img.id === imagen.id);
    if (index > -1) {
      currentFuncion.value.imagenes.splice(index, 1);
    }
    
    loadFunciones();
  } catch (error) {
    alert('Error eliminando foto');
  }
};

const closePhotosModal = () => {
  showPhotosModal.value = false;
  currentFuncion.value = null;
  uploadPreviews.value = [];
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
  background: #f5f5f5;
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

.btn-primary {
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: transform 0.2s;
}

.btn-primary:hover {
  transform: translateY(-2px);
}

.data-table {
  width: 100%;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.data-table th,
.data-table td {
  padding: 1rem;
  text-align: left;
}

.data-table th {
  background: #f8f9fa;
  font-weight: 600;
  color: #495057;
  text-transform: uppercase;
  font-size: 0.875rem;
}

.data-table tbody tr {
  border-bottom: 1px solid #e9ecef;
  transition: background 0.2s;
}

.data-table tbody tr:hover {
  background: #f8f9fa;
}

.thumbnail {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
}

.no-image {
  width: 60px;
  height: 60px;
  background: #e9ecef;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  color: #6c757d;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 600;
}

.badge-activo {
  background: #d4edda;
  color: #155724;
}

.badge-inactivo {
  background: #f8d7da;
  color: #721c24;
}

.badge-count {
  background: #e7f3ff;
  color: #0066cc;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
  font-weight: 600;
}

.btn-edit, .btn-delete, .btn-photos {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-right: 0.5rem;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.btn-edit {
  background: #3498db;
  color: white;
}

.btn-edit:hover {
  background: #2980b9;
}

.btn-photos {
  background: #9b59b6;
  color: white;
}

.btn-photos:hover {
  background: #8e44ad;
}

.btn-delete {
  background: #e74c3c;
  color: white;
}

.btn-delete:hover {
  background: #c0392b;
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
  backdrop-filter: blur(4px);
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 16px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-large {
  max-width: 1000px;
}

.modal-content h2 {
  margin-bottom: 1.5rem;
  color: #1a1a1a;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #495057;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
}

.btn-save, .btn-cancel, .btn-close {
  flex: 1;
  padding: 0.75rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
}

.btn-save {
  background: #27ae60;
  color: white;
}

.btn-save:hover {
  background: #229954;
}

.btn-cancel, .btn-close {
  background: #95a5a6;
  color: white;
}

.btn-cancel:hover, .btn-close:hover {
  background: #7f8c8d;
}

/* Photo Management Styles */
.upload-section {
  margin-bottom: 2rem;
}

.upload-box {
  border: 3px dashed #667eea;
  border-radius: 12px;
  padding: 3rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s;
  background: #f8f9ff;
}

.upload-box:hover {
  background: #e7f3ff;
  border-color: #764ba2;
}

.upload-icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 1rem;
}

.upload-previews {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: #f8f9fa;
  border-radius: 12px;
}

.upload-previews h3 {
  margin-bottom: 1rem;
  color: #495057;
}

.preview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

.preview-item {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  background: white;
  padding: 0.5rem;
}

.preview-item img {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 0.5rem;
}

.alt-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  font-size: 0.875rem;
}

.btn-remove-preview {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  background: rgba(231, 76, 60, 0.9);
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  cursor: pointer;
  font-size: 1rem;
  line-height: 1;
}

.btn-upload {
  width: 100%;
  padding: 0.75rem;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.btn-upload:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.photos-gallery h3 {
  margin-bottom: 1rem;
  color: #495057;
}

.draggable-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
}

.photo-card {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  background: white;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s;
  cursor: move;
}

.photo-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.photo-card img {
  width: 100%;
  height: 150px;
  object-fit: cover;
}

.photo-overlay {
  position: absolute;
  top: 0;
  right: 0;
  display: flex;
  gap: 0.5rem;
  padding: 0.5rem;
}

.btn-star, .btn-delete-photo {
  background: rgba(0,0,0,0.7);
  color: white;
  border: none;
  border-radius: 6px;
  width: 32px;
  height: 32px;
  cursor: pointer;
  font-size: 1.2rem;
  transition: all 0.2s;
}

.btn-star:hover {
  background: #f39c12;
}

.btn-star.active {
  background: #f39c12;
}

.btn-delete-photo:hover {
  background: #e74c3c;
}

.photo-info {
  padding: 0.75rem;
}

.alt-input-small {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
}

.badge-principal {
  display: inline-block;
  background: #f39c12;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
}

.no-photos {
  text-align: center;
  padding: 3rem;
  color: #6c757d;
}

.no-photos p {
  font-size: 1.25rem;
}
</style>
