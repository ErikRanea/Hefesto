<template>
  <div
    class="sidebar d-flex flex-column"
    :class="{ 'expanded': isExpanded }"
    @mouseenter="expandSidebar"
    @mouseleave="collapseSidebar"
  >
    <!-- Profile Section -->
    <div class="profile-section d-flex align-items-center mb-4">
      <div class="avatar-wrapper">
        <img :src="userPictureUrl" alt="User Avatar" class="avatar" />
      </div>
      <div class="user-info ms-3" :class="{ 'show': isExpanded }">
        <h6 class="mb-0 text-white">{{ userName }} {{ userLastName }}</h6>
        <small class="text-white-50">{{ userRole }}</small>
      </div>
    </div>

    <!-- Navigation Items -->
    <nav class="nav flex-column flex-grow-1">
      <a
        v-for="(item, index) in menuItems"
        :key="index"
        href="#"
        class="nav-link d-flex align-items-center"
        :class="{ 'active': activeItem === item.name }"
        @click.prevent="activeItem = item.name"
      >
        <i :class="item.icon" class="icon-center"></i>
        <span class="ms-3" :class="{ 'show': isExpanded }">{{ item.label }}</span>
      </a>
    </nav>

    <!-- Logout Section -->
    <div class="mt-auto">
      <a 
        href="#" 
        class="nav-link d-flex align-items-center"
        @click.prevent="$emit('logout')"
      >
        <i class="bi bi-box-arrow-left icon-center"></i>
        <span class="ms-3" :class="{ 'show': isExpanded }">cerrar sesion</span>
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  userRole: {
    type: String,
    required: true,
    validator: (value) => ['administrador', 'tecnico', 'operario'].includes(value)
  },
  userName: {
    type: String,
    required: true
  },
  userLastName: {
    type: String,
    required: true
  },
  userPicture: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['logout'])

const isExpanded = ref(false)
const activeItem = ref('')

const userPictureUrl = computed(() => props.userPicture.startsWith('http') 
  ? props.userPicture 
  : `../src/assets/images/userPicture/${props.userPicture}`
)

const menuItems = computed(() => {
  const items = {
    administrador: [
      { name: 'maquinas', label: 'MAQUINAS', icon: 'bi bi-server' },
      { name: 'tickets', label: 'TICKETS', icon: 'bi bi-ticket' },
      { name: 'mantenimiento', label: 'MANTENIMIENTO', icon: 'bi bi-wrench' },
      { name: 'administracion', label: 'ADMINISTRACION', icon: 'bi bi-gear' },
      { name: 'ajustes', label: 'AJUSTES', icon: 'bi bi-gear' }
    ],
    tecnico: [
      { name: 'maquinas', label: 'MAQUINAS', icon: 'bi bi-server' },
      { name: 'tickets', label: 'TICKETS', icon: 'bi bi-ticket' },
      { name: 'mantenimiento', label: 'MANTENIMIENTO', icon: 'bi bi-wrench' },
      { name: 'mis tickets', label: 'MIS TICKETS', icon: 'bi bi-clipboard-notes' },
      { name: 'ajustes', label: 'AJUSTES', icon: 'bi bi-gear' }
    ],
    operario: [
      { name: 'maquinas', label: 'MAQUINAS', icon: 'bi bi-server' },
      { name: 'tickets', label: 'TICKETS', icon: 'bi bi-ticket' },
      { name: 'mantenimiento', label: 'MANTENIMIENTO', icon: 'bi bi-wrench' },
      { name: 'ajustes', label: 'AJUSTES', icon: 'bi bi-gear' }
    ]
  }

  return items[props.userRole] || [
    { name: 'ajustes', label: 'AJUSTES', icon: 'bi bi-gear' }
  ]
})

const expandSidebar = () => isExpanded.value = true
const collapseSidebar = () => isExpanded.value = false
</script>

<style scoped>
.sidebar {
  position: fixed;
  left: 20px;
  top: 20px;
  bottom: 20px;
  width: 70px;
  background: rgba( 255, 255, 255, 0.15 );
  backdrop-filter: blur(10px);
  border-radius: 16px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 1.5rem 1rem;
  transition: all 0.3s ease;
  overflow: hidden;
  z-index: 1000;
}

.sidebar.expanded {
  width: 270px;
}

.avatar-wrapper {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid rgba(255, 255, 255, 0.2);
  flex-shrink: 0;
}

.avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-info {
  opacity: 0;
  transition: opacity 0.3s ease;
  white-space: nowrap;
}

.user-info.show {
  opacity: 1;
}

.nav-link {
  color: white;
  padding: 0.8rem 1rem;
  border-radius: 12px;
  margin-bottom: 0.5rem;
  transition: all 0.3s ease;
  position: relative;
  text-decoration: none;
}

.nav-link:hover, 
.nav-link.active {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.icon-center {
  font-size: 1.4rem;
  position: absolute;
  transform: translateX(-50%);
}

.sidebar.expanded .icon-center {
  position: static;
  transform: none;
}

.nav-link span {
  opacity: 0;
  transition: opacity 0.3s ease;
  white-space: nowrap;
}

.nav-link span.show {
  opacity: 1;
}

.sidebar .profile-section {
  flex-direction: column;
  gap: 10px;
}

.sidebar.expanded .profile-section {
  flex-direction: row;
  gap: 15px;
}

/* For the blur effect to work properly */
:root {
  --sidebar-bg: rgba(147, 51, 234, 0.9);
}

@media (max-width: 768px) {
  .sidebar {
    left: 10px;
    top: 10px;
    bottom: 10px;
  }
}
</style>