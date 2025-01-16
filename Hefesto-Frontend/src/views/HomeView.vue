<template>
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
  <div class="dashboard min-vh-100">
    <!-- Sidebar -->
    <div class="sidebar p-4 d-flex flex-column">
      <div class="logo mb-5">
        <img src="../assets/images/icons/logos.png" alt="Logo" class="img-fluid mb-4" style="max-width: 150px;">
      </div>

      <nav class="nav flex-column">
        <router-link v-for="item in menuItems"
                     :key="item.name"
                     :to="item.to"
                     :class="['nav-link d-flex align-items-center', { active: activeItem === item.name }]"
                     @click.prevent="setActiveItem(item.name)">
          <img :src="item.icon" class="me-3" alt="" aria-hidden="true">
          {{ item.name }}
        </router-link>
      </nav>

      <button @click="logout" class="nav-link mt-auto d-flex align-items-center">
        <img src="../assets/images/icons/cerrar.svg" class="me-3" alt="" aria-hidden="true">
        Cerrar sesión
      </button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <header class="header px-4 py-3 d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Dashboard</h1>
        <div class="d-flex align-items-center gap-3">
          <div class="text-end">
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

      <!-- Content Area -->
      <div class="content p-4">
        <div class="content-panel p-4">
          <Panel />
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

const router = useRouter();
const userRole = ref(null);
const userName = ref(null);
const userLastName = ref(null);
const userPicture = ref(null);
const isLoggingOut = ref(false);
const activeItem = ref('Panel');
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const ME_URL = `${API_AUTH_URL}/v1/auth/me`;
const LOGOUT_URL = `${API_AUTH_URL}/v1/auth/logout`;

const menuItems = ref([
  { name: 'Panel', icon: '../src/assets/images/icons/panel.svg', to: '/dashboard' },
  { name: 'Tickets', icon: '../src/assets/images/icons/tickets.svg', to: '/tickets' },
  { name: 'Maquinas', icon: '../src/assets/images/icons/maquinas.svg', to: '/maquinas' },
  { name: 'Mantenimiento', icon: '../src/assets/images/icons/mantenimiento.svg', to: '/mantenimiento' },
  { name: 'Ajustes', icon: '../src/assets/images/icons/ajustes.svg', to: '/ajustes' }
]);

const setActiveItem = (itemName) => {
  activeItem.value = itemName;
};

// Computed property to construct the image path
const userImagePath = computed(() => {
  return userPicture.value ? `../src/assets/images/userpicture/${userPicture.value}` : null;
});

onMounted(async () => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      const response = await axios.get(ME_URL, {
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
});

const logout = async () => {
  isLoggingOut.value = true;
  const token = localStorage.getItem('token');
  if (token) {
    try {
      await axios.get(LOGOUT_URL, {
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
.dashboard {
  display: grid;
  grid-template-columns: 280px 1fr;
  position: relative;
  overflow: hidden;
}

/* Glassmorphic Sidebar */
.sidebar {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-right: 1px solid rgba(255, 255, 255, 0.1);
  height: 100vh;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-link {
  color: rgba(255, 255, 255, 0.7);
  padding: 0.8rem 1rem;
  margin-bottom: 0.5rem;
  border-radius: 8px;
  transition: all 0.3s;
  font-size: 0.95rem;
  text-decoration: none; /* Remove default link underline */
}

.nav-link:hover, .nav-link.active {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-link img {
  margin-right: 10px;
  vertical-align: middle;
}

/* Header */
.header {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  color: white;
}

.user-name {
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.9);
}

.user-role {
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.6);
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: rgba(255, 255, 255, 0.8);
}

.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Content Area */
.content-panel {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  min-height: calc(100vh - 160px);
}

/* Animated Background */
.dashboard::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  animation: pulse 15s infinite;
  z-index: 0;
}

@keyframes pulse {
  0% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.5); opacity: 0.2; }
  100% { transform: scale(1); opacity: 0.5; }
}

/* Make main content scrollable */
.main-content {
  overflow-y: auto;
  height: 100vh;
}
</style>