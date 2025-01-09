import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue'; // Asegúrate de que la ruta sea correcta

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    }
  ]
});

export default router;