<template>
  <div class="container-fluid h-100">
    <div v-if="!showUsuariosCrud && !showCampusCrud && !showSeccionCrud && !showMaquinaCrud">
      <div class="row">
        <div class="col-md-6 h-100">
          <div class="d-flex flex-column h-100">
            <button class="glass-card mb-4" @click="openUsuariosCrud">
              <div class="d-flex justify-content-between align-items-center h-100">
                <div class="card-body text-center">
                  <h2 class="card-text">Usuarios</h2>
                </div>
                <div class="card-body text-center">
                  <img src="../assets/images/icons/usuarios.svg" width="100" />
                </div>
              </div>
            </button>
            <button class="glass-card mb-4" @click="openCampusCrud">
              <div class="d-flex justify-content-between align-items-center h-100">
                <div class="card-body text-center">
                  <h2 class="card-text">Campuses</h2>
                </div>
                <div class="card-body text-center">
                  <img src="../assets/images/icons/campuses.svg" width="100" />
                </div>
              </div>
            </button>
          </div>
        </div>
        <div class="col-md-6 h-100">
          <div class="d-flex flex-column h-100">
            <button class="glass-card mb-4" @click="openSeccionCrud">
              <div class="d-flex justify-content-between align-items-center h-100">
                <div class="card-body text-center">
                  <h2 class="card-text">Secciones</h2>
                </div>
                <div class="card-body text-center">
                  <img width="144" src="../assets/images/icons/secciones.svg" />
                </div>
              </div>
            </button>
            <button class="glass-card mb-4" @click="openMaquinaCrud">
              <div class="d-flex justify-content-between align-items-center h-100">
                <div class="card-body text-center">
                  <h2 class="card-text">Maquinas</h2>
                </div>
                <div class="card-body text-center">
                  <img width="250" src="../assets/images/icons/maquinas.svg" />
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>

    <transition name="fade">
      <CRUDusuario v-if="showUsuariosCrud" @close="closeUsuariosCrud" :enableUser="enableUser" :disableUser="disableUser" :API_AUTH_URL="API_AUTH_URL" :IMAGE_URL="IMAGE_URL" @back-to-list="closeUsuariosCrud" />
    </transition>
    <transition name="fade">
      <CRUDcampus v-if="showCampusCrud" @close="closeCampusCrud" :enableCampus="enableCampus" :disableCampus="disableCampus" :API_AUTH_URL="API_AUTH_URL" @back-to-list="closeCampusCrud" />
    </transition>
    <transition name="fade">
      <CRUDsecciones v-if="showSeccionCrud" @close="closeSeccionCrud" :enableSeccion="enableSeccion" :disableSeccion="disableSeccion" :API_AUTH_URL="API_AUTH_URL" @back-to-list="closeSeccionCrud" />
    </transition>
    <transition name="fade">
      <CRUDmaquinas v-if="showMaquinaCrud" @close="closeMaquinaCrud" :enableMaquina="enableMaquina" :disableMaquina="disableMaquina" :API_AUTH_URL="API_AUTH_URL" @back-to-list="closeMaquinaCrud" />
    </transition>

    <MaquinasPopup
      ref="maquinasPopupRef"
      :visible="popupVisible"
      :title="popupTitle"
      :subtitle="popupSubtitle"
      :closeButtonText="popupCloseButtonText"
      :actionButtonText="popupActionButtonText"
      :token="token"
      @close-popup="closePopup"
    />
  </div>
</template>

<script setup>
import GlassmorphicPopup from "./GlassmorphicPopup.vue";
import CRUDusuario from "./CRUDusuario.vue";
import CRUDcampus from "./CRUDcampus.vue";
import CRUDsecciones from "./CRUDsecciones.vue";
import CRUDmaquinas from "./CRUDmaquinas.vue";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap/dist/js/bootstrap.js";
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import MaquinasPopup from "./MaquinasPopup.vue";

const maquinasPopupRef = ref(null);
const showUsuariosCrud = ref(false);
const showCampusCrud = ref(false);
const showSeccionCrud = ref(false);
const showMaquinaCrud = ref(false);
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const IMAGE_URL = import.meta.env.VITE_IMAGE_URL;
const ME_URL = `${API_AUTH_URL}/auth/me`;

const enableUser = async (userId) => {
  const token = localStorage.getItem('token');
  const headers = {
    Authorization: `Bearer ${token}`,
  };
  try {
    const response = await axios.put(`${API_AUTH_URL}/usuario/enable/${userId}`, {}, { headers });
    console.log('Usuario habilitado:', response.data);
  } catch (error) {
    console.error('Error al habilitar el usuario:', error);
  }
};

const disableUser = async (userId) => {
  const token = localStorage.getItem('token');
  const headers = {
    Authorization: `Bearer ${token}`,
  };
  try {
    const response = await axios.put(`${API_AUTH_URL}/usuario/disable/${userId}`, {}, { headers });
    console.log('Usuario deshabilitado:', response.data);
  } catch (error) {
    console.error('Error al deshabilitar el usuario:', error);
  }
};

const openUsuariosCrud = () => {
  showUsuariosCrud.value = true;
};

const closeUsuariosCrud = () => {
  showUsuariosCrud.value = false;
};

const enableCampus = async (campusId) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    await axios.put(`${API_AUTH_URL}/campus/enable/${campusId}`, {}, { headers });
    console.log(`Campus ${campusId} habilitado`);
  } catch (error) {
    console.error('Error al habilitar campus:', error);
    throw error;
  }
};

const disableCampus = async (campusId) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    await axios.put(`${API_AUTH_URL}/campus/disable/${campusId}`, {}, { headers });
    console.log(`Campus ${campusId} deshabilitado`);
  } catch (error) {
    console.error('Error al deshabilitar campus:', error);
    throw error;
  }
};

const openCampusCrud = () => {
  showCampusCrud.value = true;
};

const closeCampusCrud = () => {
  showCampusCrud.value = false;
};

const enableSeccion = async (seccionId) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    await axios.put(`${API_AUTH_URL}/seccion/enable/${seccionId}`, {}, { headers });
    console.log(`Seccion ${seccionId} habilitada`);
  } catch (error) {
    console.error('Error al habilitar seccion:', error);
    throw error;
  }
};

const disableSeccion = async (seccionId) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    await axios.put(`${API_AUTH_URL}/seccion/disable/${seccionId}`, {}, { headers });
    console.log(`Seccion ${seccionId} deshabilitada`);
  } catch (error) {
    console.error('Error al deshabilitar seccion:', error);
    throw error;
  }
};

const openSeccionCrud = () => {
  showSeccionCrud.value = true;
};

const closeSeccionCrud = () => {
  showSeccionCrud.value = false;
};

const enableMaquina = async (maquinaId) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    await axios.put(`${API_AUTH_URL}/maquina/enable/${maquinaId}`, {}, { headers });
    console.log(`Maquina ${maquinaId} habilitada`);
  } catch (error) {
    console.error('Error al habilitar maquina:', error);
    throw error;
  }
};

const disableMaquina = async (maquinaId) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    await axios.put(`${API_AUTH_URL}/maquina/disable/${maquinaId}`, {}, { headers });
    console.log(`Maquina ${maquinaId} deshabilitada`);
  } catch (error) {
    console.error('Error al deshabilitar maquina:', error);
    throw error;
  }
};

const openMaquinaCrud = () => {
  showMaquinaCrud.value = true;
};

const closeMaquinaCrud = () => {
  showMaquinaCrud.value = false;
};

// --- Variables del Popup ---
const popupVisible = ref(false);
const popupType = ref(null);
const popupTitle = ref(null);
const popupSubtitle = ref(null);
const popupCloseButtonText = ref(null);
const popupActionButtonText = ref(null);
const token = ref(null);
const userPicture = ref(null);
const userName = ref(null);

// --- Funciones del Popup ---
const openPopup = (type) => {
  popupVisible.value = true;
  popupType.value = type;

  if (type === "perfil") {
    popupTitle.value = "Editar perfil";
    popupSubtitle.value = "Edita tu perfil";
    popupCloseButtonText.value = "Cerrar";
    popupActionButtonText.value = "Guardar";
  } else if (type === "contraseña") {
    popupTitle.value = "Cambiar contraseña";
    popupSubtitle.value = "Introduce tu nueva contraseña";
    popupCloseButtonText.value = "Cerrar";
    popupActionButtonText.value = "Guardar";
  } else if (type === "fondo") {
    popupTitle.value = "Cambiar fondo";
    popupSubtitle.value = "Añade la descripción de tu fondo";
    popupCloseButtonText.value = "Cerrar";
    popupActionButtonText.value = "Aceptar";
  } else if (type === "maquinas") {
    popupTitle.value = "Maquinas";
    popupSubtitle.value = "Listado maquinas";
    popupCloseButtonText.value = "Cerrar";
    popupActionButtonText.value = "Aceptar";
    maquinasPopupRef.value.fetchData();
  }
};

const closePopup = () => {
  popupVisible.value = false;
};

const userImagePath = computed(() => {
  return userPicture.value
    ? `../src/assets/images/userpicture/${userPicture.value}`
    : null;
});

onMounted(async () => {
  token.value = localStorage.getItem("token");
  if (token.value) {
    try {
      const response = await axios.get(ME_URL, {
        headers: {
          Authorization: `Bearer ${token.value}`,
        },
      });
      userName.value = response.data.name;
      userPicture.value = response.data.foto_perfil;
    } catch (error) {
      console.error("Error fetching user data:", error);
    }
  }
});
</script>

<style scoped>
.glass-card {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  width: 100%;
  height: 300px; /* Altura más grande */
  cursor: pointer;
  transition: transform 0.2s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
}

.glass-card:hover {
  transform: scale(1.05);
}

.card-text {
  margin-top: 0;
  color: #333;
  font-size: 2rem; /* Texto más grande */
}

.card-body {
  padding: 24px; /* Padding más grande */
  text-align: center;
}

.fade-enter-from {
  opacity: 0;
}

.fade-enter-active {
  transition: opacity 0.3s;
}

.fade-enter-to {
  opacity: 1;
}

.fade-leave-from {
  opacity: 1;
}

.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-leave-to {
  opacity: 0;
}
</style>