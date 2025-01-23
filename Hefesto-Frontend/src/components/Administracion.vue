<template>
    <div class="container-fluid h-100">
        <div class="row">
            <div class="col-md-6 h-100">
                <div class="d-flex flex-column h-100">
                    <button class="glass-card mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-body text-center">
                                <h2 class="card-text">Usuarios</h2>
                            </div>
                            <div class="card-body text-center">
                                <img src="../assets/images/icons/usuarios.svg">
                            </div>
                        </div>
                    </button>
                    <button class="glass-card mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-body text-center">
                                <h2 class="card-text">Campuses</h2>
                            </div>
                            <div class="card-body text-center">
                                <img src="../assets/images/icons/campuses.svg">
                            </div>
                        </div>
                    </button>
                    <button class="glass-card">
                        <div class="d-flex justify-content-center align-items-center text-center">
                            <div class="card-body text-center">
                                <h2 class="card-text">Secciones</h2>
                            </div>
                            <div class="card-body text-center">
                                <img width="144" src="../assets/images/icons/secciones.svg">
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <button class="glass-card mb-4" @click="openPopup('maquinas')">
                    <div class="justify-content-between align-items-center mb-3">
                        <div class="card-body text-center">
                            <h2 class="card-text">Maquinas</h2>
                        </div>
                        <div class="card-body-2 text-center">
                            <img width="250" src="../assets/images/icons/maquinas.svg">
                        </div>
                    </div>
                </button>
                <button class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-body text-center">
                            <h2 class="card-text">Mantenimientos preventivos</h2>
                        </div>
                        <div class="card-body text-center">
                            <img width="180" src="../assets/images/icons/mantenimiento.svg">
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
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
</template>

<script setup>
import GlassmorphicPopup from './GlassmorphicPopup.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import MaquinasPopup from './MaquinasPopup.vue';
const maquinasPopupRef = ref(null);

const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const ME_URL = `${API_AUTH_URL}/auth/me`;

// --- Variables del Popup ---
const popupVisible = ref(false);
const popupType = ref(null);
const popupTitle = ref(null);
const popupSubtitle = ref(null);
const popupCloseButtonText = ref(null);
const popupActionButtonText = ref(null);
const token = ref(null)
const userPicture = ref(null);
const userName = ref(null);


// --- Funciones del Popup ---
const openPopup = (type) => {
    popupVisible.value = true;
    popupType.value = type;

    if (type === 'perfil') {
        popupTitle.value = 'Editar perfil';
        popupSubtitle.value = 'Edita tu perfil';
        popupCloseButtonText.value = 'Cerrar';
        popupActionButtonText.value = 'Guardar';
    } else if (type === 'contraseña') {
        popupTitle.value = 'Cambiar contraseña';
        popupSubtitle.value = 'Introduce tu nueva contraseña';
        popupCloseButtonText.value = 'Cerrar';
        popupActionButtonText.value = 'Guardar';
    } else if (type === 'fondo') {
        popupTitle.value = 'Cambiar fondo';
        popupSubtitle.value = 'Añade la descripción de tu fondo';
        popupCloseButtonText.value = 'Cerrar';
        popupActionButtonText.value = 'Aceptar';
    }
    else if (type === 'maquinas'){
        popupTitle.value = 'Maquinas';
        popupSubtitle.value = 'Listado maquinas';
        popupCloseButtonText.value = 'Cerrar';
        popupActionButtonText.value = 'Aceptar';
         maquinasPopupRef.value.fetchData();
    }
};

const closePopup = () => {
    popupVisible.value = false;
};
 const userImagePath = computed(() => {
  return userPicture.value ? `../src/assets/images/userpicture/${userPicture.value}` : null;
});
onMounted(async () => {
    token.value = localStorage.getItem('token');
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
           console.error('Error fetching user data:', error);
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
        height: auto;
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
    }

    .glass-card:hover {
        transform: scale(1.05);
    }

    .card-text {
        margin-top: 0;
        color: #333;
    }

    .card-body {
        padding: 32px;
    }

    .card-body-2 {
        padding: 120px;
    }

</style>