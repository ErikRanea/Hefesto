<template>
    <GlassmorphicPopup
    :visible="visible"
    :title="title"
    :subtitle="subtitle"
    :closeButtonText="closeButtonText"
    :actionButtonText="actionButtonText"
     @close="closePopup"
    >
    <template #popup-content>
          <div class="mb-3 d-flex justify-content-between">
                <select v-model="selectedCampus" class="form-select">
                    <option :value="null">Selecciona un Campus</option>
                    <option v-for="campus in campusList" :key="campus.id_campus" :value="campus.nombre_campus">
                        {{ campus.nombre_campus }}
                    </option>
                </select>
                <select v-model="selectedSeccion" class="form-select">
                    <option :value="null">Selecciona una Sección</option>
                    <option v-for="seccion in seccionList" :key="seccion.id_seccion" :value="seccion.nombre_seccion">
                        {{ seccion.nombre_seccion }}
                    </option>
                </select>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Nombre de la máquina" v-model="searchName">
                <button @click="searchMachines" class="btn btn-outline-secondary">Buscar</button>
            </div>
           <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
            <div v-for="maquina in maquinas" :key="maquina.id_maquina" class="col d-flex justify-content-center">
                <div class="card glassmorphic-card mb-4">
                    <div class="d-flex justify-content-center">
                        <h5>{{ maquina.nombre_maquina }}</h5>
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
const MAQUINA_URL = `${API_AUTH_URL}/maquina/all`;
const CAMPUS_URL = `${API_AUTH_URL}/campus/all`;
const SECCION_URL = `${API_AUTH_URL}/seccion/all`;

const props = defineProps({
    visible: Boolean,
    title: String,
    subtitle: String,
    closeButtonText: String,
    actionButtonText: String,
    token: String,
});

const emit = defineEmits(['close-popup']);

// --- Variables de Maquinas, Campus y Secciones ---
const maquinas = ref([]);
const campusList = ref([]);
const seccionList = ref([]);
const selectedCampus = ref(null);
const selectedSeccion = ref(null);
const searchName = ref('');

// --- Funciones del Popup ---
const closePopup = () => {
    emit('close-popup');
};

// --- Funciones para Maquinas, Campus y Secciones ---
const fetchMaquinas = async (filters = null) => {
    try {
        const response = await axios.post(`${MAQUINA_URL}`, filters, {
             headers: {
                Authorization: `Bearer ${props.token}`,
            },
        });
        maquinas.value = response.data.data;
    } catch (error) {
        console.error('Error fetching maquina data:', error);
    }
};


const fetchCampus = async () => {
     try {
          const response = await axios.get(`${CAMPUS_URL}`, {
            headers: {
                 Authorization: `Bearer ${props.token}`,
            },
        });
          campusList.value = response.data.data;
    } catch (error) {
        console.error('Error fetching campus data:', error);
    }
};

const fetchSeccion = async () => {
    try {
         const response = await axios.post(`${SECCION_URL}`,{}, {
            headers: {
                Authorization: `Bearer ${props.token}`,
            },
        });
        seccionList.value = response.data.data;
    } catch (error) {
        console.error('Error fetching seccion data:', error);
    }
};


const searchMachines = async () => {
        const selectedCampusObject = campusList.value.find(campus => campus.nombre_campus === selectedCampus.value);
        const selectedSeccionObject = seccionList.value.find(seccion => seccion.nombre_seccion === selectedSeccion.value);

        const filters = {
            id_campus: selectedCampusObject ? selectedCampusObject.id_campus : null,
            id_seccion: selectedSeccionObject ? selectedSeccionObject.id_seccion : null,
            nombre_maquina: searchName.value
        };
    fetchMaquinas(filters);
};

const getStatusClass = (habilitado, parada) => {
    if (parada) {
        return 'btn-danger';
    } else if (habilitado) {
        return 'btn-success';
    } else {
        return 'btn-warning';
    }
};
const getStatusText = (habilitado, parada) => {
    if (parada) {
        return 'Parada';
    } else if (habilitado) {
        return 'Habilitada';
    } else {
        return 'Deshabilitada';
    }
};

   const fetchData = async () =>{
        await Promise.all([ fetchCampus(), fetchSeccion(),fetchMaquinas()]);
    }

    defineExpose({fetchData})
</script>

<style scoped>

</style>