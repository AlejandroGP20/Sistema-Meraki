import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
  },
  {
    path: '/funciones',
    name: 'Funciones',
    component: () => import('../views/Funciones.vue'),
  },
  {
    path: '/funcion/:id',
    name: 'FuncionDetalle',
    component: () => import('../views/FuncionDetalle.vue'),
  },
  {
    path: '/reservar/:funcionId',
    name: 'Reservar',
    component: () => import('../views/Reservar.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/mis-reservas',
    name: 'MisReservas',
    component: () => import('../views/MisReservas.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: () => import('../views/admin/Dashboard.vue'),
    meta: { requiresAuth: true, roles: ['admin', 'encargado'] },
  },
  {
    path: '/admin/funciones',
    name: 'AdminFunciones',
    component: () => import('../views/admin/Funciones.vue'),
    meta: { requiresAuth: true, roles: ['admin', 'encargado'] },
  },
  {
    path: '/admin/reservas',
    name: 'AdminReservas',
    component: () => import('../views/admin/Reservas.vue'),
    meta: { requiresAuth: true, roles: ['admin', 'encargado'] },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // Si hay una posición guardada (navegación con botones del navegador)
    if (savedPosition) {
      return savedPosition;
    }
    // Si la ruta tiene un hash (ancla), hacer scroll a ese elemento
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      };
    }
    // Por defecto, hacer scroll al inicio de la página
    return { top: 0, behavior: 'smooth' };
  },
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } else if (to.meta.roles && !authStore.hasRole(to.meta.roles)) {
    next('/');
  } else {
    next();
  }
});

export default router;
