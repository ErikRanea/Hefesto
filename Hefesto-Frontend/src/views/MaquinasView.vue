<template>
  <div class="home-view">
    <Sidebar v-if="userRole" :user-role="userRole"
      :user-name="userName"
      :user-last-name="userLastName"
      :user-Picture="userPicture"
      @logout="logout" />
    <div v-else-if="isLoggingOut" class="loading-container d-flex justify-content-center align-items-center">
      <ProgressBarLoader />
    </div>
    <div v-else class="loading-container d-flex justify-content-center align-items-center">
      <ProgressBarLoader />
    </div>
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 justify-content-center">
          <div v-for="maquina in maquinas.slice(0,8)" :key="maquina.id_maquina" class="col d-flex justify-content-center">
            <div class="machine-card">
              <div class="d-flex justify-content-between">
                <h5>MAQUINA {{ maquina.nombre_maquina }}</h5>
              </div>
              <div class="d-flex flex-column">
                <div class="d-flex justify-content-between">
                  <span>ID:</span>
                  <span>{{ maquina.id_maquina }}</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Número interno:</span>
                  <span>{{ maquina.numero_interno }}</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Tipo:</span>
                  <span>{{ maquina.tipo_maquina }}</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Prioridad:</span>
                  <span>{{ maquina.prioridad }}</span>
                </div>
                <div class="machine-status-button">
                  <button :class="getStatusClass(maquina.habilitado, maquina.parada)" class="btn w-100" disabled>
                    {{ getStatusText(maquina.habilitado, maquina.parada) }}
                  </button>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import ProgressBarLoader from '../components/ProgressBarLoader.vue';
import Sidebar from '../components/Sidebar.vue';

export default {
    components: {
      ProgressBarLoader,
      Sidebar,
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

  data() {
    return {
      maquinas: [],
      loading: false,
      error: null,
    };
  },
  mounted() {
      this.fetchMaquinas();
  },
  methods: {
    async fetchMaquinas(){
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/maquinas');
        this.maquinas = response.data;
      } catch(error) {
          console.error('Error fetching maquinas', error);
          this.error = 'Error al cargar las máquinas. Intenta nuevamente.'
      } finally{
          this.loading = false;
      }
    },
    getStatusClass(habilitado, parada) {
          if (parada) {
              return "btn-warning"
          }
        return habilitado ? "btn-success" : "btn-danger";
      },
      getStatusText(habilitado, parada) {
           if (parada) {
             return "Parada"
         }
        return habilitado ? "Disponible" : "No disponible";
      },
  },
};
</script>

<style scoped>

.home-view {
    background: url(https://images.wallpapersden.com/image/download/abstract-purple-waves_a2ZlZ2WUmZqaraWkpJRnamtlrWZpaWU.jpg);
    background-position: center;
    background-size: cover;
    color: white;
    height: 100vh;
    width: 100vw;
    display: flex;
    flex-direction: row;
    align-items: stretch;
}

.container {
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 1em;
    background-size: cover;
    background-position: center;
    flex-direction: row;
}

.loading-container {
    height: 100%;
    flex-grow: 1;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

.machine-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  padding: 20px;
  width: 270px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px; /* Modified border-radius to be consistent on all corners */
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  transition: .4s ease-in-out;
}

.machine-card:hover {
  transform: scale(1.05);
}

</style>