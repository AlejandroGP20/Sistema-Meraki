<template>
  <div class="auth-page">
    <div class="auth-card">
      <h2>Iniciar Sesión</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" type="email" required />
        </div>
        <div class="form-group">
          <label>Contraseña</label>
          <input v-model="form.password" type="password" required />
        </div>
        <div v-if="error" class="error">{{ error }}</div>
        <button type="submit" class="btn-submit" :disabled="loading">
          <span v-if="loading">Ingresando...</span>
          <span v-else>Ingresar</span>
        </button>
      </form>
      <p class="link-text">
        ¿No tienes cuenta? <router-link to="/register">Regístrate</router-link>
      </p>
      <p class="link-text">
        <router-link to="/" class="guest-link">Continuar como invitado</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const form = ref({
  email: '',
  password: '',
});

const error = ref('');
const loading = ref(false);

const handleLogin = async () => {
  try {
    error.value = '';
    loading.value = true;
    console.log('Intentando login con:', form.value.email);
    
    await authStore.login(form.value);
    
    console.log('Login exitoso, usuario:', authStore.user);
    console.log('Roles:', authStore.userRoles);
    
    // Redirigir según el rol
    if (authStore.hasRole(['admin', 'encargado'])) {
      console.log('Redirigiendo a /admin');
      router.push('/admin');
    } else {
      console.log('Redirigiendo a /');
      router.push('/');
    }
  } catch (err) {
    console.error('Error en login:', err);
    error.value = err.response?.data?.message || 'Error al iniciar sesión';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.auth-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  width: 100%;
  max-width: 400px;
}

.auth-card h2 {
  margin-bottom: 1.5rem;
  text-align: center;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.error {
  color: #e74c3c;
  margin-bottom: 1rem;
  text-align: center;
}

.btn-submit {
  width: 100%;
  padding: 0.75rem;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-submit:hover:not(:disabled) {
  background: #5568d3;
}

.btn-submit:disabled {
  background: #95a5a6;
  cursor: not-allowed;
}

.link-text {
  text-align: center;
  margin-top: 1rem;
}

.link-text a {
  color: #667eea;
  text-decoration: none;
  font-weight: 500;
}

.link-text a:hover {
  text-decoration: underline;
}

.guest-link {
  color: #95a5a6;
  font-size: 0.95rem;
}

.guest-link:hover {
  color: #7f8c8d;
}
</style>
