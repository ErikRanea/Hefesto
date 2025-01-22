<template>
    <div class="container-fluid h-100">
        <div class="row">
            <div class="col-md-6 h-100">
                <button class="glass-card mb-4" @click="openPopup('usuarios')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-body text-center">
                            <h2 class="card-text">Usuarios</h2>
                        </div>
                        <div class="card-body text-center">
                            <img src="../assets/images/icons/usuarios.svg">
                        </div>
                    </div>
                </button>
                <button class="glass-card mb-4" @click="openPopup('campuses')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-body text-center">
                            <h2 class="card-text">Campuses</h2>
                        </div>
                        <div class="card-body text-center">
                            <img src="../assets/images/icons/campuses.svg">
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-md-6">
                <button class="glass-card mb-4" @click="openPopup('secciones')">
                    <div class="justify-content-center align-items-center text-center">
                        <div class="card-body justify-content-center align-items-center mb-3">
                            <h2 class="card-text">Secciones</h2>
                        </div>
                        <div class="card-body-2">
                            <img src="../assets/images/icons/secciones.svg">
                        </div>
                    </div>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="glass-card mb-4" @click="openPopup('maquinas')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-body text-center">
                            <h2 class="card-text">Maquinas</h2>
                        </div>
                        <div class="card-body text-center">
                            <img width="200" src="../assets/images/icons/maquinas.svg">
                        </div>
                    </div>
                </button>
            </div>
            <div class="col">
                <button class="glass-card mb-4" @click="openPopup('mantenimientos')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-body-3 text-center">
                            <h2 class="card-text">Mantenimiento preventivo</h2>
                        </div>
                        <div class="card-body text-center">
                            <img width="177" src="../assets/images/icons/mantenimiento.svg">
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <GlassmorphicPopup
        :visible="popupVisible"
        :title="popupTitle"
        :subtitle="popupSubtitle"
        :closeButtonText="popupCloseButtonText"
        :actionButtonText="popupActionButtonText"
        :imageUrl="userImagePath"
        @close="closePopup"
        @action="handleAction"
        @image-changed="updateProfileImage"
    >
        <template #popup-content v-if="popupType !== 'perfil'">
            <div v-if="popupType === 'contraseña'">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" class="form-control" placeholder="Tu contraseña">
            </div>
            <div v-else-if="popupType === 'fondo'">
                <textarea id="descripcion" class="form-control" placeholder="Descripción"></textarea>
            </div>
            <div v-else-if="popupType === 'usuarios'">
            </div>
            <div v-else-if="popupType === 'campuses'">
            </div>
            <div v-else-if="popupType === 'secciones'">
            </div>
            <div v-else-if="popupType === 'maquinas'">

                

            </div>
            <div v-else-if="popupType === 'mantenimientos'">
            </div>
        </template>
    </GlassmorphicPopup>
</template>

<script setup>
    import GlassmorphicPopup from './GlassmorphicPopup.vue';
    import 'bootstrap/dist/css/bootstrap.css';
    import 'bootstrap/dist/js/bootstrap.js';
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';
    
    const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
    const ME_URL = `${API_AUTH_URL}/auth/me`;
    const UPDATE_PROFILE_IMAGE_URL = `${API_AUTH_URL}/auth/update-profile-image`;
    
    const popupVisible = ref(false);
    const popupType = ref(null)
    const popupTitle = ref(null)
    const popupSubtitle = ref(null)
    const popupCloseButtonText = ref(null)
    const popupActionButtonText = ref(null)
    const userPicture = ref(null);
    const userName = ref(null);
    const userLastName = ref(null);
    
    const openPopup = (type) => {
        popupVisible.value = true;
        popupType.value = type;
    
        if (type === 'perfil'){
            popupTitle.value = 'Editar perfil'
            popupSubtitle.value = 'Edita tu perfil'
            popupCloseButtonText.value = 'Cerrar'
            popupActionButtonText.value = 'Guardar'
        }
        else if(type === 'contraseña'){
            popupTitle.value = 'Cambiar contraseña'
            popupSubtitle.value = 'Introduce tu nueva contraseña'
            popupCloseButtonText.value = 'Cerrar'
            popupActionButtonText.value = 'Guardar'
        }
        else if (type === 'fondo'){
            popupTitle.value = 'Cambiar fondo'
            popupSubtitle.value = 'Añade la descripción de tu fondo'
            popupCloseButtonText.value = 'Cerrar'
            popupActionButtonText.value = 'Aceptar'
        }
        else if (type === 'maquinas'){
            popupTitle.value = 'Maquinas'
            popupSubtitle.value = 'Listado de maquinas'
            popupCloseButtonText.value = 'Cerrar'
            popupActionButtonText.value = 'Aceptar'
        }
    };
    
    const closePopup = () => {
        popupVisible.value = false;
    };
    
    
    const handleAction = async() => {
        if(popupType.value === 'perfil'){
            // Handle profile picture update logic here
        } else{
            alert(`Action clicked on ${popupType.value}`)
        }
        closePopup();
    };
    
        const updateProfileImage = async (imageURL) => {
            if (!imageURL) return;
            const token = localStorage.getItem('token');
            try {
            const formData = new FormData();
            const response = await fetch(imageURL);
                const blob = await response.blob();
    
            const file = new File([blob], 'profile-image', {type: 'image/*'});
                formData.append('profile_image', file);
                const response_upload = await axios.post(UPDATE_PROFILE_IMAGE_URL, formData,{
                        headers: {
                            Authorization: `Bearer ${token}`,
                            'Content-Type': 'multipart/form-data',
                        },
                    }
            );
    
                userPicture.value = response_upload.data.foto_perfil
                alert('Profile image uploaded successfully!');
            } catch (error) {
            console.error('Error updating profile image:', error);
            alert('Failed to upload profile image!');
            }
        };
    
    
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
            userName.value = response.data.name;
            userLastName.value = response.data.primer_apellido;
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
        padding: 77px;
    }

    .card-body-3 {
        padding: 32px;
        width: 330px;
    }

</style>