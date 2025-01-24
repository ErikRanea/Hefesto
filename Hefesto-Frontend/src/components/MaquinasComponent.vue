<template>
    <div class="container-fluid h-100">
         <div class="row">
          <div class="col list-body">
            <div v-if="!selectedMaquina" >
             <div class="mb-4 g-4">
                <h3>Listado De Máquinas</h3>
             </div>
         <div class="mb-3 d-flex flex-column">
             <select v-model="selectedCampus" class="form-select mb-4">
                 <option :value="null">Selecciona un Campus</option>
                 <option v-for="campus in campusList" :key="campus.id_campus" :value="campus.nombre_campus">
                    {{ campus.nombre_campus }}
                 </option>
             </select>
             <select v-model="selectedSeccion" class="form-select mb-2">
                <option :value="null">Selecciona una Sección</option>
                 <option v-for="seccion in seccionList" :key="seccion.id_seccion" :value="seccion.nombre_seccion">
                    {{ seccion.nombre_seccion }}
                </option>
             </select>
          </div>
            <div class="input mb-3">
                <input type="text" class="form-control" placeholder="Nombre de la máquina" v-model="searchName">
            </div>
          <div class="row">
              <div v-for="maquina in maquinas" :key="maquina.id_maquina" class="col-3 d-flex justify-content-center">
                  <button  @click="showMachineDetails(maquina)" class="glass-card mb-4">
                    <h5>{{ maquina.nombre_maquina }}</h5>
                 </button>
              </div>
             </div>
         <div class="d-flex flex-column gap-4">
             <button @click="searchMachines" class="popup-btn primary">Buscar</button>
             <button class="popup-btn primary" @click="$emit('close-component')">Salir</button>
         </div>
        </div>
        <component
           v-else
           :is="activeComponent"
           :maquina="selectedMaquina"
           @close-details="closeDetails"
           @maquina-updated="handleMachineUpdate"
         />
        </div>
      </div>
   </div>
</template>

<script setup>
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import MaquinaDetails from './MaquinaDetails.vue';
import CustomSelect from './CustomSelect.vue';

const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const MAQUINA_URL = `${API_AUTH_URL}/maquina/all`;
const CAMPUS_URL = `${API_AUTH_URL}/campus/all`;
const SECCION_URL = `${API_AUTH_URL}/seccion/all`;

const props = defineProps({
  token: String,
});

const emit = defineEmits(['close-component'])
// --- Variables de Maquinas, Campus y Secciones ---
const maquinas = ref([]);
const campusList = ref([]);
const seccionList = ref([]);
const selectedCampus = ref(null);
const selectedSeccion = ref(null);
const searchName = ref('');

// --- Variables para el componente dinamico ---
 const selectedMaquina = ref(null);
 const activeComponent = ref(null);

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

// --- Funciones para mostrar y cerrar los detalles de la maquina  ---
const showMachineDetails = (maquina) => {
  selectedMaquina.value = maquina
  activeComponent.value = MaquinaDetails;
};

const closeDetails = () =>{
  selectedMaquina.value = null;
  activeComponent.value = null;
};

const handleMachineUpdate = (maquina) => {
  //Lógica para actualizar
    console.log(maquina)
   selectedMaquina.value = null;
   activeComponent.value = null;
   fetchMaquinas()
}

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

onMounted(async () => {
  await Promise.all([fetchCampus(), fetchSeccion(), fetchMaquinas()]);
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
 width: 250px;
 margin: 1em;
 height: 120px;
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

 .popup-btn {
     background: none;
     border: none;
     text-align: center;
     font-size: 1rem;
     padding: 0.8rem 1.8rem;
     border-radius: 2rem;
     cursor: pointer;
 }

 .popup-btn.primary {
     background-color: rgba(255, 255, 255, 0.8);
     color: #000000;
 }

 .list-body {
     width: 700px;
     min-height: 300px;
     background: rgba(255, 255, 255, 0.7);
     box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
     backdrop-filter: blur(18px);
     -webkit-backdrop-filter: blur(18px);
     border: 1px solid rgba(255, 255, 255, 0.18);
     border-radius: 1.5rem;
     padding: 2rem;
     z-index: 10;
     color: #333;
     display: flex;
     flex-direction: column;
     overflow: hidden;
 }

 .form-group {
     margin-bottom: 1rem;
 }

 .form-group label {
     display: block;
     margin-bottom: 0.5rem;
     color: #666;
 }
</style>