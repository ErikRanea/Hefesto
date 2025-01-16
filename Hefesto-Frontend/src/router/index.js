import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import axios from 'axios';
import HomeView from '../views/HomeView.vue';
import MaquinasView from '../views/MaquinasView.vue';
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
    path: '/maquinasview',
    component: MaquinasView,  
    meta: { requiresAuth: true },
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

const rutasAdmin = [
  '/admin',
];

const rutasGenericas = [
  '/home'
];

/*
router.beforeEach(async (to, from, next) => {
  const publicPages = ['/login'];
  const authRequired = !publicPages.includes(to.path);
  const { isValid, isAdmin } = await fetchUserRole();

  if (authRequired && !isValid) {
    sessionStorage.removeItem('token');
    return next('/login');
  }

  if (to.meta.requiresAdmin && !isAdmin) {
    alert('Necesita ser administrador para acceder a esta página');
    return next('/home');
  }

  next();
})
*/

const comprobarToken = async () => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      const response = await axios.get(urlBack + '/v1/auth/validate-token', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      console.log('Token válido:', response.data);
      return{
        isValid: true,
        rol: response.data.data.rol,
      } 
    }
    catch (error) {
      console.error('Error al validar el token:', error);
      return {
        isValid: false,
        rol: false,
      };
    }
  }
  else {
    return{
      isValid: false,
      rol: false,
    };
  }
ç
};


// Añade un guard de navegación global
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.matched.some(record => record.meta.estarAutenticado)) {
    if (token) {
      try {
        const { isValid, rol } = await comprobarToken();
        // Si la validación es exitosa, permite el acceso
        const permisoAdmin = rutasAdmin.includes(to.path);

        if (permisoAdmin && rol !== 'administrador') {
          alert('No tienes permisos para acceder a esta página');
          next('/home');
        } else {
          next();
        }

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