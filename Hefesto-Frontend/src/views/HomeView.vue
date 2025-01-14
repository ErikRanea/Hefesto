<template>
    <div class="home-view">
      <div class="top-bar">
        <div class="left-items">
          <span>MAQUINAS</span>
          <span>TICKETS</span>
          <span>MANTENIMIENTO</span>
        </div>
        <div class="center-logo">
          HEFESTO
        </div>
        <div class="right-items">
          <span>AJUSTES</span>
          <button @click="logout" class="logout-button">CERRAR SESION</button>
        </div>
        <!-- Pop-up de carga -->
        <div v-if="loading" class="loading-popup">
          <div class="spinner"></div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { ref } from 'vue';

export default {
  setup() {
    const router = useRouter();
    const loading = ref(false);

    // El método logout envía el TOKEN para cerrar la sesión
    const logout = async () => {
      const token = localStorage.getItem('token');
      if (token) {
        try {
          // Muestra el pop-up de carga
          loading.value = true;

          const logoutUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/logout';
          await axios.get(logoutUrl, {  
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          // Clear the token from localStorage after successful logout
          localStorage.removeItem('token');
          localStorage.removeItem('token_type');
          // Redirect to the login page
          router.push('/');
          console.log('Sesión cerrada correctamente');
        } catch (error) {
          console.error('Error al cerrar sesión:', error);
          // Handle error scenarios, e.g., display an error message
        } finally {
          // Oculta el pop-up de carga después de que la operación de logout haya terminado
          loading.value = false;
        }
      } else {
        console.warn('No token found in localStorage.');
        // Optionally redirect to the login page or handle the case where no token is present.
        router.push('/');
      }
    };

    return {
      logout,
      loading,
    };
  },
};
  </script>
  
  <style scoped>
  .loading-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .spinner {
    border: 16px solid #f3f3f3;
    border-top: 16px solid #3498db;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .home-view {
    background-color: #1e1e1e;
    color: white;
    height: 100vh;
    width: 100vw;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  
  .top-bar {
    display: flex;
    justify-content: space-between; /* Keep space between for left and right items */
    align-items: center;
    width: 100%;
    padding: 10px 20px;
    border-bottom: 1px solid #444;
  }
  
  .left-items, .right-items {
    display: flex;
    gap: 20px;
    font-family: 'Helvetica', sans-serif;
    font-size: 14px;
  }
  
  .center-logo {
    font-family: 'aegis1e', sans-serif;
    font-size: 24px;
    position: absolute; /* Use absolute positioning to center */
    left: 50%;
    transform: translateX(-50%); /* Center horizontally */
  }
  
  .logout-button {
    background-color: transparent;
    color: white;
    border: none;
    font-family: 'Helvetica', sans-serif;
    font-size: 14px;
    cursor: pointer;
    margin-left: 20px; /* Add some spacing between Ajustes and Cerrar Sesión */
  }
  </style>