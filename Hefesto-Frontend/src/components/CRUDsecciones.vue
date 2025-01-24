<template>
    <div class="glassmorphic-container">
      <div v-if="currentView === 'list'" class="seccion-list">
            <h2 class="list-title">Lista de Secciones</h2>
            <button class="glassmorphic-btn add-seccion-btn" @click="registerModalVisible = true">
                <img src="../assets/images/icons/secciones.svg" alt="Añadir Sección" class="btn-icon" />
                Añadir Sección
            </button>
             <!-- Barra de búsqueda -->
            <div class="search-bar">
                <input type="text" v-model="searchQuery" placeholder="Buscar por nombre" class="glassmorphic-input"/>
                  <CustomSelect
                    :options="campusOptions"
                    :modelValue="selectedCampus"
                     @update:modelValue="handleFilterCampusSelect"
                    placeholder="Filtrar por campus"
                    class="glassmorphic-select"
                />
            </div>
            <div class="table-container">
                <table class="glassmorphic-table">
                    <thead>
                    <tr>
                         <th>
                            <input type="checkbox" @change="selectAll" v-model="allSelected" class="glassmorphic-checkbox" />
                        </th>
                        <th>Nombre</th>
                          <th>Campus</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="seccion in filteredSeccion" :key="seccion.id">
                        <td>
                            <input type="checkbox" :value="seccion.id" @change="selectSeccion" v-model="selectedSeccion" class="glassmorphic-checkbox" />
                        </td>
                        <td>{{ seccion.nombre_seccion }}</td>
                        <td>{{ getCampusName(seccion.id_campus) }}</td>
                        <td class="action-cell">
                            <label class="switch">
                                <input type="checkbox" :checked="seccion.habilitado === 1" @change="toggleSeccionStatus(seccion)" />
                                <span class="slider"></span>
                            </label>
                            <button class="glassmorphic-btn edit-btn" @click="handleEditSeccion(seccion)">Editar</button>
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
            <button class="glassmorphic-btn danger-btn" @click="deleteSelectedSeccion" :disabled="selectedSeccion.length === 0">Deshabilitar Seleccionados</button>
        </div>
       <teleport to="body">
           <GlassmorphicPopup
            :visible="registerModalVisible"
            title="Registrar Sección"
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
                <input type="text" id="nombre" v-model="seccionData.nombre_seccion" class="glassmorphic-input" />
            </div>
               <div class="form-group">
                <label for="campus">Campus:</label>
                <CustomSelect
                    :options="campusOptions"
                    :modelValue="seccionData.id_campus"
                    @update:modelValue="handleCampusSelect"
                    class="glassmorphic-select"
                />
            </div>
           </template>
         </GlassmorphicPopup>
       </teleport>
       <teleport to="body">
            <GlassmorphicPopup
              :visible="editModalVisible"
              title="Editar Sección"
              closeButtonText="Cancelar"
              actionButtonText="Guardar"
              @close="closeEditModal"
              @action="updateSeccion"
              closeButtonStyle="cancel"
              actionButtonStyle="primary"
            >
              <template #popup-content>
  
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" id="edit_nombre" v-model="editSeccionData.nombre_seccion" class="glassmorphic-input" />
                </div>
                 <div class="form-group">
                <label for="edit_campus">Campus:</label>
                <CustomSelect
                    :options="campusOptions"
                    :modelValue="editSeccionData.id_campus"
                    @update:modelValue="handleEditCampusSelect"
                    class="glassmorphic-select"
                />
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
  import CustomSelect from './CustomSelect.vue';
  
  const toast = useToast();
  const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
  const SECCION_STORE_URL = `${API_AUTH_URL}/seccion/store`;
  const SECCION_ALL_URL = `${API_AUTH_URL}/seccion/all`;
  const SECCION_UPDATE_URL = `${API_AUTH_URL}/seccion/update`;
  const SECCION_DELETE_URL = `${API_AUTH_URL}/seccion/delete`;
  const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
  const SECCION_ENABLE_URL = `${API_AUTH_URL}/seccion/enable`;
  const SECCION_DISABLE_URL = `${API_AUTH_URL}/seccion/disable`;
  const seccionData = ref({
    nombre_seccion: '',
      id_campus: null
  });
  const currentView = ref('list');
  const secciones = ref([]);
  const selectedSeccion = ref([]);
  const allSelected = ref(false);
  const emit = defineEmits(['close', 'back-to-list'])
  const campusOptions = ref([]);
  const props = defineProps({
      enableSeccion: {
          type: Function,
      },
      disableSeccion:{
          type: Function,
      }
  });
  
  const searchQuery = ref(''); // Para la barra de búsqueda
  const selectedCampus = ref(null);
  const filteredSeccion = computed(() => {
     if(!secciones.value) {
      return [];
    }
    let filtered = [...secciones.value];
      if (searchQuery.value) {
        const searchTerm = searchQuery.value.toLowerCase();
          filtered = filtered.filter(seccion => {
              const seccionName = `${seccion.nombre_seccion}`.toLowerCase();
              return seccionName.includes(searchTerm);
          });
      }
      
        if (selectedCampus.value) {
          filtered = filtered.filter(seccion => seccion.id_campus === selectedCampus.value);
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
  const handleCampusSelect = (campusId) => {
  seccionData.value.id_campus = campusId;
  };
   const handleEditCampusSelect = (campusId) => {
   editSeccionData.value.id_campus = campusId;
  };
  
  const registerModalVisible = ref(false);
  
  
  const handleAction = async () => {
    try {
        const token = localStorage.getItem('token');
        const headers = {
            Authorization: `Bearer ${token}`,
        };
          if (!seccionData.value.nombre_seccion) {
              alert('El campo Nombre es obligatorio.');
              return; // Detiene la ejecución si el nombre está vacío
          }
        if (!seccionData.value.id_campus) {
            alert('El campo Campus es obligatorio.');
            return; // Detiene la ejecución si el campus está vacío
        }
  
        const response = await axios.post(SECCION_STORE_URL, seccionData.value, { headers });
        console.log('Registro exitoso:', response.data);
         toast.success("Sección registrada exitosamente!");
  
        // Restablecer los valores de seccionData a sus valores iniciales
        seccionData.value = {
            nombre_seccion: '',
              id_campus: null
        };
         registerModalVisible.value = false;
         fetchSecciones();
    } catch (error) {
        console.error('Error al registrar seccion:', error.response ? error.response.data : error);
      toast.error('Error al registrar seccion: ' + (error.response ? JSON.stringify(error.response.data) : error));
    }
  };
  // Función para obtener el nombre del campus
  const getCampusName = (campusId) => {
      const campus = campusOptions.value.find((c) => c.id === campusId);
      return campus ? campus.label : 'Desconocido';
  };
  // Función para obtener los secciones de la API
  const fetchSecciones = async (page = 1) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
       const response = await axios.get(`${SECCION_ALL_URL}?per_page=20&page=${page}`, { headers });
       console.log('secciones response: ', response.data);
           if(response.data.data){
              secciones.value = response.data.data;
              pagination.value = {
                  current_page: response.data.data.current_page,
                  last_page: response.data.data.last_page,
                  per_page: response.data.data.per_page,
                  total: response.data.data.total,
              }
           }else{
                secciones.value = response.data;
                 pagination.value = {
                  current_page: 1,
                  last_page: 1,
                  per_page: 20,
                  total: response.data.length,
              }
           }
  } catch (error) {
    console.error('Error al obtener secciones:', error);
     toast.error('Error al obtener secciones: ' + error.message);
  }
  };
  
  // Función para seleccionar un seccion
  const selectSeccion = (event) => {
    const seccionId = parseInt(event.target.value);
    const index = selectedSeccion.value.indexOf(seccionId);
  
  if (event.target.checked) {
    // Add the seccion if not already selected
    if(index === -1){
        selectedSeccion.value.push(seccionId);
    }
  } else {
    // Remove the seccion if selected
     if (index !== -1) {
        selectedSeccion.value.splice(index, 1);
    }
  }
  console.log('Secciones seleccionadas:', selectedSeccion.value);
  };
  
  // Función para seleccionar todos los secciones
  const selectAll = () => {
      if(!secciones.value){
          selectedSeccion.value = [];
          return;
      }
  if (allSelected.value) {
    selectedSeccion.value = secciones.value.map((seccion) => parseInt(seccion.id));
  } else {
    selectedSeccion.value = [];
  }
  console.log('Secciones seleccionadas:', selectedSeccion.value);
  };
  
  // Computed para sincronizar el checkbox selectAll
  allSelected.value = computed({
  get: () =>  secciones.value && selectedSeccion.value.length === secciones.value.length,
  set: (val) => {
    if (val) {
          if(secciones.value){
                selectedSeccion.value = secciones.value.map((seccion) => parseInt(seccion.id));
          }
      
    } else {
      selectedSeccion.value = [];
    }
  },
  });
  
  
  const toggleSeccionStatus = async (seccionItem) => {
    try {
      if (seccionItem.habilitado === 1) {
           const token = localStorage.getItem('token');
              const headers = {
                  Authorization: `Bearer ${token}`,
              };
           await axios.put(`${SECCION_DISABLE_URL}/${seccionItem.id}`,{},{headers});
           seccionItem.habilitado = 0;
             console.log(`Seccion ${seccionItem.id} deshabilitado`);
      } else {
          const token = localStorage.getItem('token');
              const headers = {
                  Authorization: `Bearer ${token}`,
              };
             await axios.put(`${SECCION_ENABLE_URL}/${seccionItem.id}`,{},{headers});
          seccionItem.habilitado = 1;
            console.log(`Seccion ${seccionItem.id} habilitado`);
      }
    } catch(error){
        console.error('Error al cambiar el estado del seccion en la vista:', error);
    }
  };
  
  const editModalVisible = ref(false);
  const editSeccionData = ref({
  id:'',
  nombre_seccion: '',
      id_campus: null,
  });
  
  const handleEditSeccion = async (seccionItem) => {
    try{
        const token = localStorage.getItem('token');
      const headers = {
          Authorization: `Bearer ${token}`,
      };
       // Corrected URL to: api/v1/seccion/show/{seccion}
      const response = await axios.get(`${API_AUTH_URL}/seccion/show/${seccionItem.id}`,{headers})
        if (response.data.data) {
            const seccionData = response.data.data
            editSeccionData.value={
                id: seccionData.id,
                nombre_seccion: seccionData.nombre_seccion,
                id_campus: seccionData.id_campus
                }
           editModalVisible.value = true;
        } else {
            console.error('Error: La respuesta de la API no tiene datos:', response);
             toast.error("Error: la respuesta del API es incorrecta");
        }
     } catch (error) {
        console.error('Error al obtener datos de la seccion:', error);
         toast.error('Error al obtener datos de la seccion: ' + error.message);
    }
  
  console.log('Editing seccion:', seccionItem);
  };
  const updateSeccion = async () =>{
    try {
        const token = localStorage.getItem('token');
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const payload = {...editSeccionData.value}
        delete payload.id;
      const response = await axios.put(`${SECCION_UPDATE_URL}/${editSeccionData.value.id}`,payload,{headers})
         console.log("Respuesta de la api: ", response);
         toast.success("Sección modificada exitosamente!");
        closeEditModal();
        fetchSecciones(pagination.value.current_page)
    }
    catch (error) {
        console.error('Error al actualizar seccion:', error);
      toast.error('Error al actualizar seccion: ' +  (error.response ? JSON.stringify(error.response.data) : error));
    }
  
  }
  const closeEditModal = () => {
  editModalVisible.value = false;
  };
  const deleteSelectedSeccion = async () => {
      if (confirm('¿Estás seguro de que quieres deshabilitar las secciones seleccionadas?')) {
          try {
              const token = localStorage.getItem('token');
              const headers = {
                  Authorization: `Bearer ${token}`,
              };
  
              // Eliminar seccion individualmente
              for (const seccionId of selectedSeccion.value) {
                   await axios.put(`${API_AUTH_URL}/seccion/update/${seccionId}`, { habilitado: 0 }, { headers });
                  console.log(`Seccion ${seccionId} deshabilitada`);
              }
             toast.success('Secciones deshabilitadas exitosamente.');
             selectedSeccion.value = [];
              fetchSecciones(pagination.value.current_page) // Recargar la lista de secciones
          } catch (error) {
              console.error('Error al deshabilitar las secciones:', error);
              toast.error('Error al deshabilitar las secciones: ' + error.message);
          }
      }
  };
  const handleFilterCampusSelect = (campusId) =>{
    selectedCampus.value = campusId;
  }
  const pagination = ref({
      current_page: 1,
      last_page: null,
      per_page: 20,
      total: null
  })
  const changePage = (page) =>{
      if(page > 0 && page <= pagination.value.last_page){
          fetchSecciones(page);
      }
  }
  onMounted(async () => {
      try {
          const token = localStorage.getItem('token');
          const headers = {
              Authorization: `Bearer ${token}`,
          };
          const response = await axios.get(CAMPUS_ALL_URL, { headers });
          campusOptions.value = response.data.data.map((campus) => ({
              id: campus.id,
              label: campus.nombre_campus,
          }));
      } catch (error) {
          console.error('Error al obtener campus:', error);
          toast.error('Error al obtener campus: ' + error.message);
      }
     await fetchSecciones();
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
  
  
  .add-seccion-btn {
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