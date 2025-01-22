<template>
    <div class="container-fluid h-100">
        <div class="row">
            <div class="col-md-6 h-100">
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
            </div>
            <div class="col-md-6">
                <button class="glass-card mb-4">
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
                <button class="glass-card mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="card-body-3 text-center">
                            <h2 class="card-text">Mantenimientos preventivos</h2>
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
            <div v-else-if="popupType === 'maquinas'" class="popup-maquina">
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

    .popup-maquina {
        height: 400px;
        width: 500px;
    }

</style>