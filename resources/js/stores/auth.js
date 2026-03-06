import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRoles: (state) => state.user?.roles?.map(r => r.name) || [],
  },

  actions: {
    async register(data) {
      const response = await axios.post('/api/register', data);
      this.token = response.data.token;
      this.user = response.data.user;
      localStorage.setItem('token', this.token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    },

    async login(credentials) {
      const response = await axios.post('/api/login', credentials);
      this.token = response.data.token;
      this.user = response.data.user;
      localStorage.setItem('token', this.token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    },

    async logout() {
      await axios.post('/api/logout');
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      delete axios.defaults.headers.common['Authorization'];
    },

    async checkAuth() {
      if (this.token) {
        try {
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          const response = await axios.get('/api/me');
          this.user = response.data;
        } catch (error) {
          this.token = null;
          this.user = null;
          localStorage.removeItem('token');
        }
      }
    },

    hasRole(roles) {
      if (!Array.isArray(roles)) roles = [roles];
      return roles.some(role => this.userRoles.includes(role));
    },
  },
});
