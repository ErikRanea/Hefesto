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
   <span></span>
</div>
  <div class="dashboard min-vh-100">
    <!-- Sidebar -->
    <div class="sidebar p-0 d-flex flex-column">  <!-- Removed p-4-->
      <div class="logo mb-5 p-4"> <!-- Added p-4 to logo -->
        <img src="../assets/images/icons/logos.png" alt="Logo" class="img-fluid mb-4" style="max-width: 150px;">
      </div>

      <nav class="nav flex-column">
        <a v-for="item in filteredMenuItems"
             :key="item.name"
             href="#"
             :class="['nav-link d-flex align-items-center', { active: activeItem === item.name }]"
             @click.prevent="setActiveItem(item.name)">
          <img :src="item.icon" class="me-3" alt="" aria-hidden="true">
          {{ item.name }}
        </a>
      </nav>

      <button @click="logout" class="nav-link mt-auto d-flex align-items-center">
        <img src="../assets/images/icons/cerrar.svg" class="me-3" alt="" aria-hidden="true">
        Cerrar sesión
      </button>
    </div>

    <!-- Main Content -->
    <div class="main-content d-flex flex-column"> <!-- Added d-flex flex-column -->
      <!-- Header -->
      <header class="header px-4 py-3 d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0"></h1>
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
      <div class="content flex-grow-1 d-flex flex-column"> <!-- Added d-flex flex-column and flex-grow-1-->
        <div class="content-panel p-4 d-flex flex-column">
          <component :is="componenteActual" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineAsyncComponent , onMounted, computed } from 'vue'
import { useRouter } from 'vue-router';
import axios from 'axios';


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

const Panel = defineAsyncComponent(() => import('../components/Panel.vue'));
const Incidencias = defineAsyncComponent(() => import('../components/Incidencias.vue'));
const Mantenimiento = defineAsyncComponent(() => import('../components/Mantenimiento.vue'));
const Ajustes = defineAsyncComponent(() => import('../components/Ajustes.vue'));
const Administracion = defineAsyncComponent(() => import('../components/Administracion.vue'));


const baseMenuItems = [
  { name: 'Incidencias', icon: '../src/assets/images/icons/tickets.svg', to: '/incidencias' },
  { name: 'Mantenimiento', icon: '../src/assets/images/icons/mantenimiento.svg', to: '/mantenimiento' },
  { name: 'Ajustes', icon: '../src/assets/images/icons/ajustes.svg', to: '/ajustes' }
];

const allMenuItems = {
  administrador: [
    { name: 'Panel', icon: '../src/assets/images/icons/panel.svg', to: '/dashboard' },
    ...baseMenuItems,
    { name: 'Administracion', icon: '../src/assets/images/icons/administracion.svg', to: '/administracion' },
  ],
  operario: [
    { name: 'Panel', icon: '../src/assets/images/icons/panel.svg', to: '/dashboard' },
    { name: 'Incidencias', icon: '../src/assets/images/icons/tickets.svg', to: '/incidencias' },
    { name: 'Mantenimiento', icon: '../src/assets/images/icons/mantenimiento.svg', to: '/mantenimiento' },
    { name: 'Ajustes', icon: '../src/assets/images/icons/ajustes.svg', to: '/ajustes' }
  ],
  tecnico: [
    { name: 'Panel', icon: '../src/assets/images/icons/panel.svg', to: '/dashboard' },
    { name: 'Incidencias', icon: '../src/assets/images/icons/tickets.svg', to: '/incidencias' },
    { name: 'Mis incidencias', icon: '../src/assets/images/icons/mistickets.svg', to: '/mis-incidencias' },
    { name: 'Mantenimiento', icon: '../src/assets/images/icons/mantenimiento.svg', to: '/mantenimiento' },
    { name: 'Ajustes', icon: '../src/assets/images/icons/ajustes.svg', to: '/ajustes' }
  ],
};

const filteredMenuItems = computed(() => {
  return allMenuItems[userRole.value] || [];
});

const componenteActual = computed(() => {
  switch (activeItem.value) {
    case 'Panel':
      return Panel;
    case 'Incidencias':
      return Incidencias;
    case 'Maquinas':
      return Maquinas;
    case 'Mantenimiento':
      return Mantenimiento;
    case 'Ajustes':
      return Ajustes;
    case 'Administracion':
      return Administracion;
    default:
      return Panel; // Default to Panel component if none of the above match
  }
});

const setActiveItem = (itemName) => {
  activeItem.value = itemName;
  console.log('Active item:', itemName);
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

<style lang="scss" scoped>
$white-01: rgba(255, 255, 255, 0.1);
$white-03: rgba(255, 255, 255, 0.3);
$white-05: rgba(255, 255, 255, 0.5);
$white-06: rgba(255, 255, 255, 0.6);
$white-07: rgba(255, 255, 255, 0.7);
$white-08: rgba(255, 255, 255, 0.8);
$white-09: rgba(255, 255, 255, 0.9);


.dashboard {
  display: grid;
  grid-template-columns: 280px 1fr;
  position: relative;
  overflow: hidden;

  &::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 50% 50%, $white-01 0%, transparent 50%);
    animation: pulse 15s infinite;
    z-index: 0;
  }
}


/* Glassmorphic Sidebar */
.sidebar {
  background: $white-05;
  backdrop-filter: blur(10px);
  border-right: 1px solid $white-01;
  height: 100vh;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-link {
  color: $white-07;
  padding: 0.8rem 1rem;
  margin-bottom: 0.5rem;
  border-radius: 8px;
  transition: all 0.3s;
  font-size: 0.95rem;
  text-decoration: none;

  &:hover, &.active {
    background: $white-01;
    color: white;
  }

  img {
    margin-right: 10px;
    vertical-align: middle;
  }
}

/* Header */
.header {
  background: $white-05;
  backdrop-filter: blur(10px);
  color: white;
}

.user-name {
  font-size: 0.9rem;
  color: $white-09;
}

.user-role {
  font-size: 0.8rem;
  color: $white-06;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: $white-01;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: $white-08;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

/* Content Area */
.content-panel {
  background: $white-03;
  backdrop-filter: blur(10px);
  border: 1px solid $white-01;
  flex-grow: 1;
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
  display: flex;
  flex-direction: column;
}

.logo img {
  max-width: 100px;
}

</style>