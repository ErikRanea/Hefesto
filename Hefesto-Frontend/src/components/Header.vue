<template>
    <div class="header-wrapper">
        <div class="top-bar">
          <div class="left-items" v-if="userRole === 'administrador'">
            <span>MAQUINAS</span>
            <span>TICKETS</span>
            <span>MANTENIMIENTO</span>
            <span>ADMINISTRACION</span>
          </div>
          <div class="left-items" v-else-if="userRole === 'tecnico'">
            <span>MAQUINAS</span>
            <span>TICKETS</span>
            <span>MANTENIMIENTO</span>
            <span>MIS TICKETS</span>
          </div>
          <div class="left-items" v-else-if="userRole === 'operario'">
            <span>MAQUINAS</span>
            <span>TICKETS</span>
            <span>MANTENIMIENTO</span>
          </div>
          <div class="center-logo">
            HEFESTO
          </div>
          <div class="right-items">
            <span>AJUSTES</span>
            <button @click="logout" class="logout-button" :disabled="isLoggingOut">CERRAR SESION</button>
             <div v-if="isLoggingOut" class="loading-container-header">
               <ProgressBarLoader />
             </div>
          </div>
        </div>
      </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import ProgressBarLoader from './ProgressBarLoader.vue';
  
  export default {
      components: {
          ProgressBarLoader,
      },
    setup() {
       const router = useRouter();
      const userRole = ref(null);
      const meUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/me';
      const isLoggingOut = ref(false);
  
      onMounted(async () => {
        const token = localStorage.getItem('token');
        if (token) {
          try {
            const response = await axios.get(meUrl, {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            });
            userRole.value = response.data.rol;
          } catch (error) {
            console.error('Error fetching user role:', error);
            router.push('/');
          }
        } else {
          router.push('/');
        }
      });
  
      const logout = async () => {
        isLoggingOut.value = true;
        const token = localStorage.getItem('token');
        if (token) {
          try {
            const logoutUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/logout';
            await axios.get(logoutUrl, {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            });
            localStorage.removeItem('token');
            localStorage.removeItem('token_type');
             router.push('/');
            console.log('Sesión cerrada correctamente');
          } catch (error) {
            console.error('Error al cerrar sesión:', error);
          } finally {
            isLoggingOut.value = false;
          }
        } else {
          console.warn('No token found in localStorage.');
           router.push('/');
          isLoggingOut.value = false;
        }
      };
      return { logout, userRole, isLoggingOut};
    },
  };
  </script>
  
  <style scoped>
  .header-wrapper {
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 100;
    background-color: #1e1e1e; /* Asegura que el fondo del header sea consistente */
  }
  
  .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 10px 20px;
    border-bottom: 1px solid #444;
  }
  
  .left-items {
    display: flex;
    gap: 20px;
    font-family: 'Helvetica', sans-serif;
    font-size: 14px;
  }
  
  .center-logo {
    font-family: 'aegis1e', sans-serif;
    font-size: 24px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
  }
  
  .right-items {
    display: flex;
    align-items: center;
    gap: 20px;
    font-family: 'Helvetica', sans-serif;
    font-size: 14px;
    position: relative;
  }
  
  .logout-button {
    background-color: transparent;
    color: white;
    border: none;
    font-family: 'Helvetica', sans-serif;
    font-size: 14px;
    cursor: pointer;
    margin-left: 20px;
  }
  
  .loading-container-header {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
  }
  
  </style>