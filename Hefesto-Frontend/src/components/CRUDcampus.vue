<template>
    <div class="glassmorphic-container">
      <div v-if="currentView === 'list'" class="campus-list">
            <h2 class="list-title">Lista de Campus</h2>
            <button class="glassmorphic-btn add-campus-btn" @click="registerModalVisible = true">
                <img src="../assets/images/icons/campus.svg" alt="Añadir Campus" class="btn-icon" />
                Añadir Campus
            </button>
             <!-- Barra de búsqueda -->
            <div class="search-bar">
                <input type="text" v-model="searchQuery" placeholder="Buscar por nombre" class="glassmorphic-input"/>
            </div>
            <div class="table-container">
                <table class="glassmorphic-table">
                    <thead>
                    <tr>
                         <th>
                            <input type="checkbox" @change="selectAll" v-model="allSelected" class="glassmorphic-checkbox" />
                        </th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="campus in filteredCampus" :key="campus.id">
                        <td>
                            <input type="checkbox" :value="campus.id" @change="selectCampus" v-model="selectedCampus" class="glassmorphic-checkbox" />
                        </td>
                        <td>{{ campus.nombre_campus }}</td>
                        <td class="action-cell">
                            <label class="switch">
                                <input type="checkbox" :checked="campus.habilitado === 1" @change="toggleCampusStatus(campus)" />
                                <span class="slider"></span>
                            </label>
                            <button class="glassmorphic-btn edit-btn" @click="handleEditCampus(campus)">Editar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
          <!-- Paginacion-->
           <div class="pagination">
                  <button
                          @click="changePage(pagination.current_page - 1)"
                          :disabled="pagination.current_page === 1"
                          class="glassmorphic-btn pagination-btn"
                  >
                    Anterior
                  </button>
  
                 <span class="pagination-info">Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
  
                  <button
                          @click="changePage(pagination.current_page + 1)"
                          :disabled="pagination.current_page === pagination.last_page"
                          class="glassmorphic-btn pagination-btn"
                  >
                  Siguiente
                  </button>
          </div>
            <button v-if="currentView === 'list'" class="glassmorphic-btn" @click="goBackToList">Volver</button>
             <button class="glassmorphic-btn danger-btn" @click="deleteSelectedCampus" :disabled="selectedCampus.length === 0">Deshabilitar Seleccionados</button>
        </div>
       <teleport to="body">
           <GlassmorphicPopup
            :visible="registerModalVisible"
            title="Registrar Campus"
            closeButtonText="Cancelar"
             actionButtonText="Registrar"
            @close="goBack"
            @action="handleAction"
            closeButtonStyle="cancel"
            actionButtonStyle="primary"
         >
          <template #popup-content>
  
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" v-model="campusData.nombre_campus" class="glassmorphic-input" />
            </div>
  
           </template>
         </GlassmorphicPopup>
       </teleport>
       <teleport to="body">
            <GlassmorphicPopup
              :visible="editModalVisible"
              title="Editar Campus"
              closeButtonText="Cancelar"
              actionButtonText="Guardar"
              @close="closeEditModal"
              @action="updateCampus"
              closeButtonStyle="cancel"
              actionButtonStyle="primary"
            >
              <template #popup-content>
  
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" id="edit_nombre" v-model="editCampusData.nombre_campus" class="glassmorphic-input" />
                </div>
  
              </template>
           </GlassmorphicPopup>
      </teleport>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue';
  import axios from 'axios';
  import { useToast } from 'vue-toastification';
  import GlassmorphicPopup from './GlassmorphicPopup.vue';
  
  const toast = useToast();
  const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
  const CAMPUS_STORE_URL = `${API_AUTH_URL}/campus/store`;
    const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
  const CAMPUS_UPDATE_URL = `${API_AUTH_URL}/campus/update`;
    const CAMPUS_ENABLE_URL = `${API_AUTH_URL}/campus/enable`;
      const CAMPUS_DISABLE_URL = `${API_AUTH_URL}/campus/disable`;
  const CAMPUS_DELETE_URL = `${API_AUTH_URL}/campus/delete`;
  const campusData = ref({
    nombre_campus: '',
  });
  const currentView = ref('list');
  const campus = ref([]);
  const selectedCampus = ref([]);
  const allSelected = ref(false);
  const emit = defineEmits(['close', 'back-to-list'])
  
  const props = defineProps({
      enableCampus: {
          type: Function,
      },
      disableCampus:{
          type: Function,
      }
  });
  
  const searchQuery = ref(''); // Para la barra de búsqueda
  const filteredCampus = computed(() => {
    let filtered = [...campus.value];
      if (searchQuery.value) {
        const searchTerm = searchQuery.value.toLowerCase();
          filtered = filtered.filter(campus => {
              const campusName = `${campus.nombre_campus}`.toLowerCase();
              return campusName.includes(searchTerm);
          });
      }
    return filtered;
  });
  
  
  const openRegisterView = () => {
  currentView.value = 'register';
  };
  const goBack = () => {
    currentView.value = 'list';
      registerModalVisible.value = false;
  
  };
  const goBackToList = () => {
  emit('back-to-list')
  }
  
  
  const registerModalVisible = ref(false);
  
  
  const handleAction = async () => {
    try {
        const token = localStorage.getItem('token');
        const headers = {
            Authorization: `Bearer ${token}`,
        };
          if (!campusData.value.nombre_campus) {
              alert('El campo Nombre es obligatorio.');
              return; // Detiene la ejecución si el nombre está vacío
          }
  
        const response = await axios.post(CAMPUS_STORE_URL, campusData.value, { headers });
        console.log('Registro exitoso:', response.data);
         toast.success("Campus registrado exitosamente!");
  
        // Restablecer los valores de campusData a sus valores iniciales
        campusData.value = {
            nombre_campus: '',
        };
         registerModalVisible.value = false;
         fetchCampus();
    } catch (error) {
        console.error('Error al registrar campus:', error.response ? error.response.data : error);
      toast.error('Error al registrar campus: ' + (error.response ? JSON.stringify(error.response.data) : error));
    }
  };
  
  // Función para obtener los campus de la API
  const fetchCampus = async (page = 1) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
       const response = await axios.get(`${CAMPUS_ALL_URL}?per_page=20&page=${page}`, { headers });
       console.log('campus response: ',response.data)
      campus.value = response.data.data;
       pagination.value = {
           current_page: response.data.current_page,
           last_page: response.data.last_page,
           per_page: response.data.per_page,
           total: response.data.total,
       }
  } catch (error) {
    console.error('Error al obtener campus:', error);
     toast.error('Error al obtener campus: ' + error.message);
  }
  };
  
  // Función para seleccionar un campus
  const selectCampus = (event) => {
    const campusId = parseInt(event.target.value);
    const index = selectedCampus.value.indexOf(campusId);
  
  if (event.target.checked) {
    // Add the campus if not already selected
    if(index === -1){
        selectedCampus.value.push(campusId);
    }
  } else {
    // Remove the campus if selected
     if (index !== -1) {
        selectedCampus.value.splice(index, 1);
    }
  }
  console.log('Campus seleccionados:', selectedCampus.value);
  };
  
  // Función para seleccionar todos los campus
  const selectAll = () => {
  if (allSelected.value) {
    selectedCampus.value = campus.value.map((campus) => parseInt(campus.id));
  } else {
    selectedCampus.value = [];
  }
  console.log('Campus seleccionados:', selectedCampus.value);
  };
  
  // Computed para sincronizar el checkbox selectAll
  allSelected.value = computed({
  get: () => selectedCampus.value.length === campus.value.length,
  set: (val) => {
    if (val) {
      selectedCampus.value = campus.value.map((campus) => parseInt(campus.id));
    } else {
      selectedCampus.value = [];
    }
  },
  });
  
  
  const toggleCampusStatus = async (campusItem) => {
    try {
      if (campusItem.habilitado === 1) {
          const token = localStorage.getItem('token');
           const headers = {
              Authorization: `Bearer ${token}`,
          };
        await axios.put(`${CAMPUS_DISABLE_URL}/${campusItem.id}`,{},{headers});
         campusItem.habilitado = 0;
           console.log(`Campus ${campusItem.id} deshabilitado`);
      } else {
            const token = localStorage.getItem('token');
           const headers = {
              Authorization: `Bearer ${token}`,
          };
          await axios.put(`${CAMPUS_ENABLE_URL}/${campusItem.id}`,{},{headers});
           campusItem.habilitado = 1;
           console.log(`Campus ${campusItem.id} habilitado`);
      }
    } catch(error){
         console.error('Error al cambiar el estado del campus en la vista:', error);
    }
  };
  
  const editModalVisible = ref(false);
  const editCampusData = ref({
  id:'',
  nombre_campus: '',
  });
  
  const handleEditCampus = async (campusItem) => {
    try{
        const token = localStorage.getItem('token');
      const headers = {
          Authorization: `Bearer ${token}`,
      };
       // Corrected URL to: api/v1/campus/show/{campus}
      const response = await axios.get(`${API_AUTH_URL}/campus/show/${campusItem.id}`,{headers})
        if (response.data.data) {
            const campusData = response.data.data
            editCampusData.value={
                id: campusData.id,
                nombre_campus: campusData.nombre_campus
                }
           editModalVisible.value = true;
        } else {
            console.error('Error: La respuesta de la API no tiene datos:', response);
             toast.error("Error: la respuesta del API es incorrecta");
        }
     } catch (error) {
        console.error('Error al obtener datos del campus:', error);
         toast.error('Error al obtener datos del campus: ' + error.message);
    }
  
  console.log('Editing campus:', campusItem);
  };
  const updateCampus = async () =>{
    try {
        const token = localStorage.getItem('token');
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const payload = {...editCampusData.value}
        delete payload.id;
      const response = await axios.put(`${CAMPUS_UPDATE_URL}/${editCampusData.value.id}`,payload,{headers})
         console.log("Respuesta de la api: ", response);
         toast.success("Campus modificado exitosamente!");
        closeEditModal();
        fetchCampus(pagination.value.current_page)
    }
    catch (error) {
        console.error('Error al actualizar campus:', error);
      toast.error('Error al actualizar campus: ' +  (error.response ? JSON.stringify(error.response.data) : error));
    }
  
  }
  const closeEditModal = () => {
  editModalVisible.value = false;
  };
  const deleteSelectedCampus = async () => {
    if (confirm('¿Estás seguro de que quieres deshabilitar los campus seleccionados?')) {
        try {
            const token = localStorage.getItem('token');
            const headers = {
                Authorization: `Bearer ${token}`,
            };
  
            // Eliminar campus individualmente
            for (const campusId of selectedCampus.value) {
                await axios.put(`${API_AUTH_URL}/campus/update/${campusId}`, { habilitado: 0 }, { headers });
                console.log(`Campus ${campusId} deshabilitado`);
            }
           toast.success('Campus deshabilitados exitosamente.');
           selectedCampus.value = [];
          fetchCampus(pagination.value.current_page) // Recargar la lista de campus
        } catch (error) {
            console.error('Error al deshabilitar los campus:', error);
            toast.error('Error al deshabilitar los campus: ' + error.message);
        }
    }
  };
  const pagination = ref({
      current_page: 1,
      last_page: null,
      per_page: 20,
      total: null
  })
  const changePage = (page) =>{
      if(page > 0 && page <= pagination.value.last_page){
          fetchCampus(page);
      }
  }
  onMounted(async () => {
     await fetchCampus();
  });
  
  </script>
  <style scoped>
  .glassmorphic-container {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  border: 1px solid rgba(255, 255, 255, 0.18);
  color: #333;
  
  }
  .pagination{
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
    align-items: center;
  }
  
  .glassmorphic-btn {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: #333;
  padding: 10px 20px;
  border-radius: 50px;
  font-weight: bold;
  transition: all 0.3s ease;
  cursor: pointer;
  }
  
  .glassmorphic-btn:hover {
  background: rgba(255, 255, 255, 0.3);
  }
  
  .glassmorphic-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
  }
  
  .glassmorphic-input,
  .glassmorphic-select {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    padding: 10px;
    color: #333;
    width: 300px;
  }
  
  
  
  .glassmorphic-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
  }
  
  .glassmorphic-table th,
  .glassmorphic-table td {
  background: rgba(255, 255, 255, 0.2);
  padding: 15px;
  text-align: left;
  border: none;
  }
  
  .glassmorphic-table th {
  font-weight: bold;
  text-transform: uppercase;
  }
  
  .glassmorphic-table tr {
  transition: all 0.3s ease;
  }
  
  .glassmorphic-table tr:hover {
  background: rgba(255, 255, 255, 0.3);
  }
  
  
  .add-campus-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  }
  
  .btn-icon {
  width: 20px;
  height: 20px;
  }
  .search-bar {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    align-items: center;
  }
  .danger-btn {
  background: rgba(220, 53, 69, 0.7);
  color: white;
  }
  
  .danger-btn:hover {
  background: rgba(220, 53, 69, 0.9);
  }
  
  .enable-btn {
  background: rgba(40, 167, 69, 0.7);
  color: white;
  }
  
  .enable-btn:hover {
  background: rgba(40, 167, 69, 0.9);
  }
  
  .form-title, .list-title {
  font-size: 24px;
  margin-bottom: 20px;
  color: #333;
  }
  
  .radio-group {
      display: flex; /* Cambia a flex */
      flex-direction: row; /* Alinear horizontalmente */
      gap: 20px; /* Añade espacio entre elementos */
      margin-bottom: 10px;
      align-items: center;
   }
  
  .radio-label {
  display: flex;
  align-items: center;
  gap: 5px;
  }
  
  .glassmorphic-checkbox {
  appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.5);
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.2);
  cursor: pointer;
  }
  
  .glassmorphic-checkbox:checked {
  background: rgba(96, 4, 132, 0.7);
  }
  
  .table-container {
  overflow-x: auto;
  margin-bottom: 20px;
  }
  .switch {
   position: relative;
   display: inline-block;
   width: 120px;
   height: 34px;
  }
  
  .switch input {
   display: none;
  }
  
  .slider {
   position: absolute;
   cursor: pointer;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background-color: #3C3C3C;
   -webkit-transition: .4s;
   transition: .4s;
   border-radius: 34px;
  }
  
  .slider:before {
   position: absolute;
   content: "";
   height: 26px;
   width: 26px;
   left: 4px;
   bottom: 4px;
   background-color: white;
   -webkit-transition: .4s;
   transition: .4s;
   border-radius: 50%;
  }
  
  input:checked + .slider {
   background-color: rgba(96, 4, 132, 1);
  }
  
  input:focus + .slider {
   box-shadow: 0 0 1px rgba(96, 4, 132, 0.719);
  }
  
  input:checked + .slider:before {
   -webkit-transform: translateX(26px);
   -ms-transform: translateX(26px);
   transform: translateX(85px);
  }
  
  /*------ ADDED CSS ---------*/
  .slider:after {
  content: 'DESABILITADO';
  color: white;
  display: block;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  left: 60%;
  font-size: 10px;
  
  }
  
  input:checked + .slider:after {
  content: 'HABILITADO';
  left: 47%;
  }
  
  /*--------- END --------*/
  .edit-btn{
    margin-left: 5px;
  background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #333;
    padding: 5px 10px;
    border-radius: 50px;
    font-weight: bold;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  .edit-btn:hover{
    background: rgba(255, 255, 255, 0.3);
  }
  .action-cell {
    display: flex;
    align-items: center;
  }
  .form-group {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
  
  }
  .form-group label {
      width: 150px;
      text-align:left;
      
  }
  
  .checkbox-wrapper-47 input[type="radio"] {
    display: none;
    visibility: hidden;
  }
  
  .checkbox-wrapper-47 label {
    position: relative;
    padding-left: 2em;
    padding-right: 1em;
    line-height: 2;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
  }
  
  .checkbox-wrapper-47 label:before {
    box-sizing: border-box;
    content: " ";
    position: absolute;
    top: 0.3em;
    left: 0;
    display: block;
    width: 1.4em;
    height: 1.4em;
    border: 2px solid #9098A9;
    border-radius: 6px;
    z-index: -1;
  }
  
   .checkbox-wrapper-47 input[type=radio]:checked + label {
       padding-left: 1em;
      color: #fff; /* Color de texto cuando está seleccionado */
  }
  .checkbox-wrapper-47 input[type=radio]:checked + label:before {
      top: 0;
      width: 100%;
      height: 2em;
      background: rgba(96, 4, 132, 1); /* Morado */
      border-color: rgba(96, 4, 132, 1); /* Morado */
  }
  
  .checkbox-wrapper-47 label,
  .checkbox-wrapper-47 label::before {
      transition: 0.25s all ease;
  }
  .checkbox-wrapper-47 {
      margin-right: 15px;
  }
  </style>