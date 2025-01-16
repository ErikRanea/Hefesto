<template>
  <div class="dashboard-container min-vh-100">
    <!-- Background -->
    <div class="background">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>

    <!-- Main Layout -->
    <div class="d-flex h-100">
      <!-- Sidebar -->
      <div class="sidebar glassmorphic-sidebar p-4">
        <div class="logo mb-5">
          <img src="../assets/images/icons/logos.png" alt="Logo" class="img-fluid mb-4" style="max-width: 150px;">
        </div>
        <nav class="nav flex-column">
          <a class="nav-link active" href="#"><img src="../assets/images/icons/panel.svg"> Panel</a>
          <a class="nav-link" href="#"><img src="../assets/images/icons/tickets.svg"> Tickets</a>
          <a class="nav-link" href="#"><img src="../assets/images/icons/maquinas.svg"> Maquinas</a>
          <a class="nav-link" href="#"><img src="../assets/images/icons/mantenimiento.svg"> Mantenimiento</a>
          <a class="nav-link" href="#"><img src="../assets/images/icons/ajustes.svg"> Ajustes</a>
        </nav>
        <div class="mt-auto">
          <button class="btn btn-link text-dark" @click="logout"><img src="../assets/images/icons/cerrar.svg"> Cerrar sesion</button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-grow-1 d-flex flex-column">
        <!-- Header -->
        <header class="header glassmorphic-header px-4 py-3 d-flex justify-content-between align-items-center">
          <h1 class="h4 mb-0">Dashboard</h1>
          <div class="d-flex align-items-center gap-2">
            <div class="d-flex flex-column align-items-end me-2">
              <span class="user-name">{{ userName }} {{ userLastName }}</span>
              <span class="user-role">{{ userRole }}</span>
            </div>
            <div class="user-avatar">
              <img :src="userImagePath" alt="User avatar" class="rounded-circle" v-if="userImagePath">
              <div v-else class="rounded-circle bg-secondary d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                <i class="bi bi-person-circle text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
          </div>
        </header>

        <!-- Content -->
        <div class="content p-4">
          <!-- Main Panel -->
          <div class="glassmorphic-main-panel p-4 mb-4">
            <Panel />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router';
import axios from 'axios';
import Panel from '../components/Panel.vue';

const userRole = ref(null);
const userName = ref(null);
const userLastName = ref(null);
const userPicture = ref(null);
const isLoggingOut = ref(false);
const router = useRouter();
const meUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/me';

// Computed property to construct the image path
const userImagePath = computed(() => {
  return userPicture.value ? `../src/assets/images/userpicture/${userPicture.value}` : null;
});

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
      userName.value = response.data.name;
      userLastName.value = response.data.primer_apellido;
      userPicture.value = response.data.foto_perfil;
    } catch (error) {
      console.error('Error fetching user data:', error);
      router.push('/');
    }
  } else {
    router.push('/');
  }
})

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
</script>

<style scoped>
.dashboard-container {
  position: relative;
  overflow: hidden;
}

.background {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #000;
  z-index: -1;
}

.glassmorphic-sidebar {
  background: rgba(255, 255, 255, 0.5) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  width: 280px; /* Define a width for the sidebar */
}

.glassmorphic-header {
  background: rgba(255, 255, 255, 0.8) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}

.content {
  padding: 20px;
}

.nav-link {
  color: rgba(0, 0, 0, 0.8);
  padding: 0.8rem 1rem;
  margin-bottom: 0.5rem;
  border-radius: 8px;
  transition: all 0.3s;
}

.nav-link:hover, .nav-link.active {
  background: rgba(0, 0, 0, 0.1);
  color: black;
}

.nav-link i {
  margin-right: 10px;
}

.card {
  border-radius: 15px;
  color: black;
}

.status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

table {
  color: rgba(255, 255, 255, 0.9) !important;
  background-color: transparent !important;
}

.table-responsive {
  max-height: calc(100vh - 300px);
  overflow-y: auto;
  background-color: transparent !important;
}

.table > :not(caption) > * > * {
  background-color: transparent !important;
}

canvas {
  max-height: 300px;
}

.table-responsive {
  max-height: calc(100vh - 300px);
  overflow-y: auto;
}

.card.h-100 {
  height: calc(100vh - 200px) !important;
}

.card-body {
  padding: 1.5rem;
}

.text-muted {
  color: rgba(255, 255, 255, 0.6) !important;
}

.h3 {
  color: rgba(255, 255, 255, 0.9);
}

.user-name {
  font-size: 0.9rem;
  color: rgba(0, 0, 0, 0.8);
  line-height: 1.2;
}

.user-role {
  font-size: 0.8rem;
  color: rgba(0, 0, 0, 0.6);
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid rgba(255, 255, 255, 0.2);
  display: flex;
  justify-content: center;
  align-items: center;
}

.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.glassmorphic-main-panel {
  background: rgba(255, 255, 255, 0.3) !important;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  border-radius: 15px;
}
</style>