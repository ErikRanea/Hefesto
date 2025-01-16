import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import axios from 'axios';
import HomeView from '../views/HomeView.vue';
import AdminView from '../views/AdminView.vue';

const urlBack = import.meta.env.VITE_API_AUTH_URL;

const routes = [
  {
    path: '/',
    component: LoginView,
  },
  {
    path: '/home',
    component: HomeView,  
    meta: { estarAutenticado: true },
  },
  {
    path: '/admin',
    component: AdminView,
    meta: { estarAutenticado: true },
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});




const comprobarToken = async () => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      const response = await axios.get(urlBack + '/v1/auth/validate-token', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      return response.data;
    }
    catch (error) {
      console.error('Error al validar el token:', error);
      return false;
    }
  }
  else {
    return false;
  }
ç
};


// Añade un guard de navegación global
router.beforeEach(async (to, from, next) => {




  const token = localStorage.getItem('token');
  if (to.matched.some(record => record.meta.estarAutenticado)) {
    if (token) {
      try {
        const response = await axios.get(urlBack + '/v1/auth/validate-token', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        // Si la validación es exitosa, permite el acceso
        next();
      } catch (error) {
        console.error('Error al validar el token:', error);
        // Si hay un error en la validación, redirige al login
        next('/');
      }
    } else {
      // Si no hay token, redirige al login
      next('/');
    }
  } else {
    // Si la ruta no requiere autenticación, permite el acceso
    next();
  }
});

export default router;