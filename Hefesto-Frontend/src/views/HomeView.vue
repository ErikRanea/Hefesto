<template>
  <div class="home-view">
    <Sidebar
      v-if="userRole"
      :user-role="userRole"
      :user-name="userName"
      :user-last-name="userLastName"
      :user-Picture="userPicture"
      @logout="logout"
    />
    <div v-else-if="isLoggingOut" class="loading-container">
      <ProgressBarLoader />
    </div>
    <div v-else class="loading-container">
      <ProgressBarLoader />
    </div>
    <Panel v-if="userRole"  />
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import ProgressBarLoader from '../components/ProgressBarLoader.vue';
import Sidebar from '../components/Sidebar.vue';
import Panel from '../components/Panel.vue'

export default {
  components: {
    ProgressBarLoader,
    Sidebar,
    Panel,
  },
  setup() {
    const router = useRouter();
    const userRole = ref(null);
    const userName = ref(null);
    const userLastName = ref(null);
    const userPicture = ref(null);
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

    return { logout, userRole, userName, userLastName,userPicture, isLoggingOut };
  },
};
</script>

<style scoped>
.home-view {
  background: url(https://images.wallpapersden.com/image/download/abstract-purple-waves_a2ZlZ2WUmZqaraWkpJRnamtlrWZpaWU.jpg);
  background-position: center;
  background-size: cover;
  color: white;
  height: 100vh; /* Ensure full viewport height */
  width: 100vw;
  display: flex;
  flex-direction: row;
  align-items: stretch; /* Make children take full height */
}

.content-wrapper {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center; /* Center content within the content area */
  align-items: center;
}

.content {
  text-align: center;
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
</style>