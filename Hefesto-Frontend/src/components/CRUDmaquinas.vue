<template>
    <div class="glassmorphic-container">
      <div v-if="currentView === 'list'" class="maquina-list">
            <h2 class="list-title">Lista de Máquinas</h2>
            <button class="glassmorphic-btn add-maquina-btn" @click="registerModalVisible = true">
                <img src="../assets/images/icons/maquinas.svg" alt="Añadir Máquina" class="btn-icon" />
                Añadir Máquina
            </button>
             <!-- Barra de búsqueda -->
            <div class="search-bar">
                <input type="text" v-model="searchQuery" placeholder="Buscar por nombre" class="glassmorphic-input"/>
                 <CustomSelect
                    :options="seccionOptions"
                    :modelValue="selectedSeccion"
                     @update:modelValue="handleFilterSeccionSelect"
                    placeholder="Filtrar por seccion"
                    class="glassmorphic-select"
                  />
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
                         <th>Número Interno</th>
                          <th>Tipo</th>
                            <th>Prioridad</th>
                        <th>Sección</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="maquina in filteredMaquina" :key="maquina.id">
                        <td>
                            <input type="checkbox" :value="maquina.id" @change="selectMaquina" v-model="selectedMaquina" class="glassmorphic-checkbox" />
                        </td>
                        <td>{{ maquina.nombre_maquina }}</td>
                        <td>{{ maquina.numero_interno }}</td>
                         <td>{{ maquina.tipo_maquina }}</td>
                          <td>{{ maquina.prioridad }}</td>
                          <td>{{ getSeccionName(maquina.id_seccion) }}</td>
                        <td class="action-cell">
                            <label class="switch">
                                <input type="checkbox" :checked="maquina.habilitado === 1" @change="toggleMaquinaStatus(maquina)" />
                                <span class="slider"></span>
                            </label>
                            <button class="glassmorphic-btn edit-btn" @click="handleEditMaquina(maquina)">Editar</button>
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
              <button class="glassmorphic-btn danger-btn" @click="deleteSelectedMaquina" :disabled="selectedMaquina.length === 0">Deshabilitar Seleccionados</button>
        </div>
       <teleport to="body">
           <GlassmorphicPopup
            :visible="registerModalVisible"
            title="Registrar Máquina"
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
                <input type="text" id="nombre" v-model="maquinaData.nombre_maquina" class="glassmorphic-input" />
            </div>
              <div class="form-group">
                  <label for="numero_interno">Numero Interno:</label>
                  <input type="number" id="numero_interno" v-model="maquinaData.numero_interno" class="glassmorphic-input" />
              </div>
               <div class="form-group">
                  <label for="tipo_maquina">Tipo:</label>
                  <input type="text" id="tipo_maquina" v-model="maquinaData.tipo_maquina" class="glassmorphic-input" />
              </div>
              <div class="form-group">
                  <label for="prioridad">Prioridad:</label>
                  <input type="number" id="prioridad" v-model="maquinaData.prioridad" class="glassmorphic-input" />
              </div>
              <div class="form-group">
                <label for="seccion">Sección:</label>
                <CustomSelect
                    :options="seccionOptions"
                    :modelValue="maquinaData.id_seccion"
                    @update:modelValue="handleSeccionSelect"
                    class="glassmorphic-select"
                />
            </div>
           </template>
         </GlassmorphicPopup>
       </teleport>
       <teleport to="body">
            <GlassmorphicPopup
              :visible="editModalVisible"
              title="Editar Máquina"
              closeButtonText="Cancelar"
              actionButtonText="Guardar"
              @close="closeEditModal"
              @action="updateMaquina"
              closeButtonStyle="cancel"
              actionButtonStyle="primary"
            >
              <template #popup-content>
  
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" id="edit_nombre" v-model="editMaquinaData.nombre_maquina" class="glassmorphic-input" />
                </div>
                  <div class="form-group">
                  <label for="edit_numero_interno">Numero Interno:</label>
                  <input type="number" id="edit_numero_interno" v-model="editMaquinaData.numero_interno" class="glassmorphic-input" />
              </div>
               <div class="form-group">
                  <label for="edit_tipo_maquina">Tipo:</label>
                  <input type="text" id="edit_tipo_maquina" v-model="editMaquinaData.tipo_maquina" class="glassmorphic-input" />
              </div>
                <div class="form-group">
                  <label for="edit_prioridad">Prioridad:</label>
                  <input type="number" id="edit_prioridad" v-model="editMaquinaData.prioridad" class="glassmorphic-input" />
              </div>
                <div class="form-group">
                <label for="edit_seccion">Sección:</label>
                <CustomSelect
                    :options="seccionOptions"
                    :modelValue="editMaquinaData.id_seccion"
                    @update:modelValue="handleEditSeccionSelect"
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
  const MAQUINA_STORE_URL = `${API_AUTH_URL}/maquina/store`;
  const MAQUINA_ALL_URL = `${API_AUTH_URL}/maquina/all`;
  const MAQUINA_UPDATE_URL = `${API_AUTH_URL}/maquina/update`;
  const MAQUINA_DELETE_URL = `${API_AUTH_URL}/maquina/delete`;
    const SECCION_ALL_URL = `${API_AUTH_URL}/seccion/all`;
    const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
      const MAQUINA_ENABLE_URL = `${API_AUTH_URL}/maquina/enable`;
  const MAQUINA_DISABLE_URL = `${API_AUTH_URL}/maquina/disable`;
  const maquinaData = ref({
      nombre_maquina: '',
      numero_interno: '',
        tipo_maquina: '',
        prioridad: '',
         id_seccion: null
  });
  const currentView = ref('list');
  const maquinas = ref([]);
  const selectedMaquina = ref([]);
  const allSelected = ref(false);
  const emit = defineEmits(['close', 'back-to-list'])
  const seccionOptions = ref([]);
  const campusOptions = ref([]);
  const props = defineProps({
      enableMaquina: {
          type: Function,
      },
      disableMaquina:{
          type: Function,
      }
  });
  const searchQuery = ref('');
  const selectedSeccion = ref(null);
  const selectedCampus = ref(null);
  const filteredMaquina = computed(() => {
      if(!maquinas.value){
          return [];
      }
    let filtered = maquinas.value;
      if (searchQuery.value) {
        const searchTerm = searchQuery.value.toLowerCase();
          filtered = filtered.filter(maquina => {
              const maquinaName = `${maquina.nombre_maquina}`.toLowerCase();
              return maquinaName.includes(searchTerm);
          });
      }
      if (selectedSeccion.value) {
          filtered = filtered.filter(maquina => maquina.id_seccion === selectedSeccion.value);
      }
        if (selectedCampus.value) {
              filtered = filtered.filter(maquina => {
                return maquina.seccion && maquina.seccion.id_campus === selectedCampus.value
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
  const handleSeccionSelect = (seccionId) => {
  maquinaData.value.id_seccion = seccionId;
  };
  const handleEditSeccionSelect = (seccionId) => {
   editMaquinaData.value.id_seccion = seccionId;
  };
  const registerModalVisible = ref(false);
  const handleAction = async () => {
      try {
          const token = localStorage.getItem('token');
          const headers = {
              Authorization: `Bearer ${token}`,
          };
          if (!maquinaData.value.nombre_maquina) {
               alert('El campo Nombre es obligatorio.');
                return;
          }
            if (!maquinaData.value.numero_interno) {
                  alert('El campo Numero Interno es obligatorio.');
                  return;
          }
              if (!maquinaData.value.prioridad) {
                  alert('El campo Prioridad es obligatorio.');
                   return;
              }
             if (!maquinaData.value.id_seccion) {
                 alert('El campo Seccion es obligatorio.');
                 return;
             }
          const response = await axios.post(MAQUINA_STORE_URL, maquinaData.value, { headers });
           console.log('Registro exitoso:', response.data);
          toast.success("Máquina registrada exitosamente!");
  
         maquinaData.value = {
                nombre_maquina: '',
               numero_interno: '',
                tipo_maquina: '',
               prioridad: '',
                id_seccion: null
           };
          registerModalVisible.value = false;
          fetchMaquinas();
      } catch (error) {
            console.error('Error al registrar máquina:', error.response ? error.response.data : error);
            toast.error('Error al registrar máquina: ' + (error.response ? JSON.stringify(error.response.data) : error));
      }
  };
  // Función para obtener el nombre de la seccion
  const getSeccionName = (seccionId) => {
   const seccion = seccionOptions.value.find((c) => c.id === seccionId);
   return seccion ? seccion.label : 'Desconocido';
  };
  // Función para obtener los maquinas de la API
  const fetchMaquinas = async (page = 1) => {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };
    const response = await axios.post(`${MAQUINA_ALL_URL}?per_page=20&page=${page}`, {}, { headers });
    
    // Directly use response.data.data since the data is in this array
    maquinas.value = response.data.data;
    
    // Simplified pagination since you're not getting pagination metadata
    pagination.value = {
      current_page: 1,
      last_page: 1,
      per_page: 20,
      total: response.data.data.length,
    }
  } catch (error) {
    console.error('Error al obtener máquinas:', error);
    toast.error('Error al obtener máquinas: ' + error.message);
  }
};
  // Función para seleccionar un maquina
  const selectMaquina = (event) => {
      const maquinaId = parseInt(event.target.value);
      const index = selectedMaquina.value.indexOf(maquinaId);
  if (event.target.checked) {
    // Add the maquina if not already selected
    if(index === -1){
        selectedMaquina.value.push(maquinaId);
    }
  } else {
    // Remove the maquina if selected
     if (index !== -1) {
        selectedMaquina.value.splice(index, 1);
    }
  }
  console.log('Maquinas seleccionadas:', selectedMaquina.value);
  };
  
  // Función para seleccionar todos los maquinas
  const selectAll = () => {
      if(!maquinas.value){
         selectedMaquina.value = [];
          return
      }
  if (allSelected.value) {
    selectedMaquina.value = maquinas.value.map((maquina) => parseInt(maquina.id));
  } else {
    selectedMaquina.value = [];
  }
  console.log('Maquinas seleccionadas:', selectedMaquina.value);
  };
  
  // Computed para sincronizar el checkbox selectAll
  allSelected.value = computed({
  get: () => maquinas.value && selectedMaquina.value.length === maquinas.value.length,
  set: (val) => {
    if (val) {
        if(maquinas.value){
            selectedMaquina.value = maquinas.value.map((maquina) => parseInt(maquina.id));
        }
    } else {
      selectedMaquina.value = [];
    }
  },
  });
  const toggleMaquinaStatus = async (maquinaItem) => {
    try {
      if (maquinaItem.habilitado === 1) {
           const token = localStorage.getItem('token');
              const headers = {
                  Authorization: `Bearer ${token}`,
              };
          await axios.put(`${MAQUINA_DISABLE_URL}/${maquinaItem.id}`,{},{headers});
         maquinaItem.habilitado = 0;
      } else {
          const token = localStorage.getItem('token');
              const headers = {
                  Authorization: `Bearer ${token}`,
              };
           await axios.put(`${MAQUINA_ENABLE_URL}/${maquinaItem.id}`,{},{headers});
            maquinaItem.habilitado = 1;
      }
    } catch(error){
        console.error('Error al cambiar el estado de la máquina en la vista:', error);
    }
  };
  const editModalVisible = ref(false);
  const editMaquinaData = ref({
  id:'',
  nombre_maquina: '',
  numero_interno: '',
   tipo_maquina: '',
   prioridad: '',
    id_seccion: null
  });
  const handleEditMaquina = async (maquinaItem) => {
    try{
        const token = localStorage.getItem('token');
      const headers = {
          Authorization: `Bearer ${token}`,
      };
       // Corrected URL to: api/v1/maquina/show/{maquina}
      const response = await axios.get(`${API_AUTH_URL}/maquina/show/${maquinaItem.id}`,{headers})
        if (response.data.data) {
            const maquinaData = response.data.data;
            editMaquinaData.value={
                id: maquinaData.id,
                nombre_maquina: maquinaData.nombre_maquina,
                  numero_interno: maquinaData.numero_interno,
                     tipo_maquina: maquinaData.tipo_maquina,
                      prioridad: maquinaData.prioridad,
                    id_seccion: maquinaData.id_seccion
                }
           editModalVisible.value = true;
        } else {
            console.error('Error: La respuesta de la API no tiene datos:', response);
             toast.error("Error: la respuesta del API es incorrecta");
        }
     } catch (error) {
        console.error('Error al obtener datos de la máquina:', error);
         toast.error('Error al obtener datos de la máquina: ' + error.message);
    }
  };
  const updateMaquina = async () =>{
    try {
        const token = localStorage.getItem('token');
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const payload = {...editMaquinaData.value}
        delete payload.id;
      const response = await axios.put(`${MAQUINA_UPDATE_URL}/${editMaquinaData.value.id}`,payload,{headers})
         console.log("Respuesta de la api: ", response);
         toast.success("Máquina modificada exitosamente!");
        closeEditModal();
       fetchMaquinas(pagination.value.current_page)
    }
    catch (error) {
        console.error('Error al actualizar máquina:', error);
          toast.error('Error al actualizar máquina: ' +  (error.response ? JSON.stringify(error.response.data) : error));
    }
  
  }
  const closeEditModal = () => {
  editModalVisible.value = false;
  };
  const deleteSelectedMaquina = async () => {
  if (confirm('¿Estás seguro de que quieres deshabilitar las máquinas seleccionadas?')) {
      try {
            const token = localStorage.getItem('token');
            const headers = {
                Authorization: `Bearer ${token}`,
            };
          for (const maquinaId of selectedMaquina.value) {
              await axios.put(`${API_AUTH_URL}/maquina/update/${maquinaId}`, { habilitado: 0 }, { headers });
             console.log(`Máquina ${maquinaId} deshabilitada`);
         }
           toast.success('Máquinas deshabilitadas exitosamente.');
           selectedMaquina.value = [];
           fetchMaquinas(pagination.value.current_page)
      } catch (error) {
          console.error('Error al deshabilitar las máquinas:', error);
          toast.error('Error al deshabilitar las máquinas: ' + error.message);
      }
  }
  };
   const handleFilterSeccionSelect = (seccionId) =>{
    selectedSeccion.value = seccionId;
  }
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
          fetchMaquinas(page);
      }
  }
  onMounted(async () => {
        try {
            const token = localStorage.getItem('token');
            const headers = {
                Authorization: `Bearer ${token}`,
            };
          const response = await axios.get(SECCION_ALL_URL, { headers });
           seccionOptions.value = response.data.data.map((seccion) => ({
                  id: seccion.id,
                  label: seccion.nombre_seccion,
              }));
              const campusResponse = await axios.get(CAMPUS_ALL_URL, { headers });
              campusOptions.value = campusResponse.data.data.map((campus) => ({
                   id: campus.id,
                  label: campus.nombre_campus,
               }));
      } catch (error) {
            console.error('Error al obtener opciones:', error);
              toast.error('Error al obtener opciones: ' + error.message);
      }
    await fetchMaquinas();
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
  
  
  .add-maquina-btn {
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