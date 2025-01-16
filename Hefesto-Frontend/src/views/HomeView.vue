<template>
  <div class="home-view">
    <div v-if="userRole && !isLoggingOut" class="content-wrapper">
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
        </div>
      </div>
      <div class="content">
        <h1>Bienvenido</h1>
        <p v-if="userRole">{{ userRole }}</p>
        <p>Haz cosas de {{ userRole }}</p>
      </div>
    </div>
    <div v-else-if="isLoggingOut" class="loading-container">
      <ProgressBarLoader />
    </div>
    <div v-else class="loading-container">
      <ProgressBarLoader />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import ProgressBarLoader from '../components/ProgressBarLoader.vue'; // Import the new component

export default {
  components: {
    ProgressBarLoader, // Register the new component
  },
  setup() {
    const router = useRouter();
    const userRole = ref(null); // Initialize userRole as null
    const meUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/me';
    const isLoggingOut = ref(false); // Add a ref for logout loading state

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
        // No token, redirect to login
        router.push('/');
      }
    });

    const logout = async () => {
      isLoggingOut.value = true; // Set loading to true when logout starts
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
          isLoggingOut.value = false; // Set loading to false after logout (success or error)
        }
      } else {
        console.warn('No token found in localStorage.');
        router.push('/');
        isLoggingOut.value = false; // Ensure loading is false even if no token
      }
    };

    return { logout, userRole, isLoggingOut };
  },
};
</script>

<style scoped>
.home-view {
  background-color: #1e1e1e;
  color: white;
  height: 100vh;
  width: 100vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center; /* Center content vertically */
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  width: 100%; /* Take full width */
  height: 100%; /* Take full height */
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

.content {
  margin-top: 20px;
  padding: 20px;
  text-align: center;
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%; /* Take full height to center vertically */
}
/* Remove the old loader styles from here */
</style>