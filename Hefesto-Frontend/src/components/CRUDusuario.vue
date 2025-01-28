<template>
  <div class="glassmorphic-container">
    <div v-if="currentView === 'list'" class="user-list">
          <h2 class="list-title">Lista de Usuarios</h2>
          <button class="glassmorphic-btn add-user-btn" @click="registerModalVisible = true">
              <img src="../assets/images/icons/usuarios.svg" alt="Añadir Usuario" class="btn-icon" />
              Añadir Usuario
          </button>
           <!-- Barra de búsqueda -->
          <div class="search-bar">
              <input type="text" v-model="searchQuery" placeholder="Buscar por nombre o apellido" class="glassmorphic-input"/>
          
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
                      <th>Foto</th>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Rol</th>
                      <th>Campus</th>
                      <th>Acciones</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="user in filteredUsers" :key="user.id">
                      <td>
                          <input type="checkbox" :value="user.id" @change="selectUser" v-model="selectedUsers" class="glassmorphic-checkbox" />
                      </td>
                      <td>
                          <img :src="props.IMAGE_URL + user.foto_perfil" alt="Foto de perfil" class="user-avatar" />
                      </td>
                      <td>{{ user.name }} {{ user.primer_apellido }} {{ user.segundo_apellido }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ user.rol }}</td>
                      <td>{{ getCampusName(user.id_campus) }}</td>
                      <td class="action-cell">
                          <label class="switch">
                              <input type="checkbox" :checked="user.habilitado === 1" @change="toggleUserStatus(user)" />
                              <span class="slider"></span>
                          </label>
                          <button class="glassmorphic-btn edit-btn" @click="handleEditUser(user)">Editar</button>
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
          <button class="glassmorphic-btn danger-btn" @click="deleteSelectedUsers" :disabled="selectedUsers.length === 0">Deshabilitar Seleccionados</button>
      </div>
     <teleport to="body">
         <GlassmorphicPopup
          :visible="registerModalVisible"
          title="Registrar Usuario"
          closeButtonText="Cancelar"
           actionButtonText="Registrar"
          @close="goBack"
          @action="handleAction"
          closeButtonStyle="cancel"
          actionButtonStyle="primary"
       >
        <template #popup-content>
          <div class="radio-group">
              <div class="checkbox-wrapper-47">
                <input type="radio" name="rol" id="tecnico" v-model="isTecnico" :value="true" />
                <label for="tecnico">Técnico</label>
              </div>
              <div class="checkbox-wrapper-47">
                <input type="radio" name="rol" id="usuario" v-model="isTecnico" :value="false" />
                <label for="usuario">Usuario</label>
              </div>
          </div>

          <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" id="nombre" v-model="userData.name" class="glassmorphic-input" />
          </div>

          <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" id="email" v-model="userData.email" class="glassmorphic-input" />
          </div>

          <div class="form-group">
              <label for="password">Contraseña:</label>
              <input type="password" id="password" v-model="userData.password" class="glassmorphic-input" />
          </div>

          <div class="form-group">
              <label for="primer_apellido">Primer Apellido:</label>
              <input type="text" id="primer_apellido" v-model="userData.primer_apellido" class="glassmorphic-input" />
          </div>

          <div class="form-group">
              <label for="segundo_apellido">Segundo Apellido:</label>
              <input type="text" id="segundo_apellido" v-model="userData.segundo_apellido" class="glassmorphic-input" />
          </div>

          <div class="form-group">
              <label for="campus">Campus:</label>
              <CustomSelect
                  :options="campusOptions"
                  :modelValue="userData.id_campus"
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
            title="Editar Usuario"
            closeButtonText="Cancelar"
            actionButtonText="Guardar"
            @close="closeEditModal"
            @action="updateUser"
            closeButtonStyle="cancel"
            actionButtonStyle="primary"
          >
            <template #popup-content>
             <div class="form-group">
              <label for="edit_nombre">Nombre:</label>
                  <input type="text" id="edit_nombre" v-model="editUserData.name" class="glassmorphic-input" />
              </div>

               <div class="form-group">
                   <label for="edit_email">Email:</label>
                   <input type="email" id="edit_email" v-model="editUserData.email" class="glassmorphic-input" />
               </div>

          <div class="form-group">
              <label for="edit_password">Contraseña:</label>
              <input type="password" id="edit_password" v-model="editUserData.password" class="glassmorphic-input" />
          </div>

           <div class="form-group">
               <label for="edit_primer_apellido">Primer Apellido:</label>
               <input type="text" id="edit_primer_apellido" v-model="editUserData.primer_apellido" class="glassmorphic-input" />
           </div>

           <div class="form-group">
               <label for="edit_segundo_apellido">Segundo Apellido:</label>
               <input type="text" id="edit_segundo_apellido" v-model="editUserData.segundo_apellido" class="glassmorphic-input" />
           </div>
            <div class="form-group">
               <label for="edit_rol">Rol:</label>
                <select id="edit_rol" v-model="editUserData.rol" class="glassmorphic-select">
                 <option value="operario">Operario</option>
                   <option value="tecnico">Técnico</option>
                  </select>
            </div>
           <div class="form-group">
              <label for="edit_campus">Campus:</label>
               <CustomSelect
                  :options="campusOptions"
                   :modelValue="editUserData.id_campus"
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
import { ref, onMounted, computed, watch } from 'vue';
import CustomSelect from './CustomSelect.vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import GlassmorphicPopup from './GlassmorphicPopup.vue';

const toast = useToast();
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const REGISTER_URL = `${API_AUTH_URL}/auth/register`;
const REGISTER_TECNICO_URL = `${API_AUTH_URL}/auth/register_tecnico`;
const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
const USUARIO_ALL_URL = `${API_AUTH_URL}/usuario/all`;
const USUARIO_DELETE_URL = `${API_AUTH_URL}/usuario/update`;
const USUARIO_UPDATE_URL = `${API_AUTH_URL}/usuario/update`;

const isTecnico = ref(false);
const userData = ref({
name: '',
email: '',
password: '',
primer_apellido: '',
segundo_apellido: '',
id_campus: null,
});

const campusOptions = ref([]);
const currentView = ref('list');
const users = ref([]);
const selectedUsers = ref([]);
const allSelected = ref(false);
const emit = defineEmits(['close', 'back-to-list'])
const props = defineProps({
  closeUsuariosCrud: {
      type: Function,
  },
   enableUser: {
      type: Function,
  },
  disableUser: {
    type: Function
 },
  API_AUTH_URL: {
          type: String,
          required: true
      },
       IMAGE_URL: {
          type: String,
          required: true
      }
});
const searchQuery = ref(''); // Para la barra de búsqueda
const selectedCampus = ref(null);
const filteredUsers = computed(() => {
  let filtered = [...users.value];
  
    if (searchQuery.value) {
      const searchTerm = searchQuery.value.toLowerCase();
        filtered = filtered.filter(user => {
            const fullName = `${user.name} ${user.primer_apellido} ${user.segundo_apellido}`.toLowerCase();
            return fullName.includes(searchTerm);
        });
    }
    
    if (selectedCampus.value) {
        filtered = filtered.filter(user => user.id_campus === selectedCampus.value);
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
userData.value.id_campus = campusId;
};
const handleEditCampusSelect = (campusId) => {
 editUserData.value.id_campus = campusId;
};
const registerModalVisible = ref(false);


const handleAction = async () => {
  try {
      const url = isTecnico.value ? REGISTER_TECNICO_URL : REGISTER_URL;
      const payload = {};

      // Debugging: Imprime los valores de los campos justo antes de construir el payload
      console.log('Valor de userData.nombre:', userData.value.name);
      console.log('Valor de userData.email:', userData.value.email);
      console.log('Valor de userData.password:', userData.value.password);
      console.log('Valor de userData.id_campus:', userData.value.id_campus);

      // Campos requeridos
      if (!userData.value.name) {
          alert('El campo Nombre es obligatorio.');
          return; // Detiene la ejecución si el nombre está vacío
      }
      payload.name = userData.value.name; // Cambiado a 'name'

      if (!userData.value.email) {
          alert('El campo Email es obligatorio.');
          return; // Detiene la ejecución si el email está vacío
      }
      payload.email = userData.value.email;

      if (!userData.value.password) {
          alert('El campo Contraseña es obligatorio.');
          return; // Detiene la ejecución si la contraseña está vacía
      }
      payload.password = userData.value.password;
   if (userData.value.id_campus) {
       payload.id_campus = Number(userData.value.id_campus);
        }
      else{
          alert('El campo Campus es obligatorio.');
          return;
      }
      // Campos opcionales
      if (userData.value.primer_apellido) {
          payload.primer_apellido = userData.value.primer_apellido;
      }
      if (userData.value.segundo_apellido) {
          payload.segundo_apellido = userData.value.segundo_apellido;
      }

      // Debugging: Imprime el payload antes de enviarlo
      console.log('Payload a enviar:', payload);

      const token = localStorage.getItem('token');
      const headers = {
          Authorization: `Bearer ${token}`,
      };

      const response = await axios.post(url, payload, { headers });
      console.log('Registro exitoso:', response.data);
       toast.success("Usuario registrado exitosamente!");

      // Restablecer los valores de userData a sus valores iniciales
      userData.value = {
          name: '',
          email: '',
          password: '',
          primer_apellido: '',
          segundo_apellido: '',
          id_campus: null,
      };
       registerModalVisible.value = false;
  } catch (error) {
      console.error('Error al registrar usuario:', error.response ? error.response.data : error);
    toast.error('Error al registrar usuario: ' + (error.response ? JSON.stringify(error.response.data) : error));
  }
};

// Función para obtener el nombre del campus
const getCampusName = (campusId) => {
const campus = campusOptions.value.find((c) => c.id === campusId);
return campus ? campus.label : 'Desconocido';
};

// Función para obtener los usuarios de la API
const fetchUsers = async (page = 1) => {
try {
  const token = localStorage.getItem('token');
  const headers = {
    Authorization: `Bearer ${token}`,
  };
     const response = await axios.get(`${USUARIO_ALL_URL}?per_page=20&page=${page}`, { headers });
    console.log(response.data.data);
    users.value = response.data.data.data;
    pagination.value = {
        current_page: response.data.data.current_page,
        last_page: response.data.data.last_page,
        per_page: response.data.data.per_page,
        total: response.data.data.total,
    }
} catch (error) {
  console.error('Error al obtener usuarios:', error);
   toast.error('Error al obtener usuarios: ' + error.message);
}
};

// Función para seleccionar un usuario
const selectUser = (event) => {
  const userId = parseInt(event.target.value);
  const index = selectedUsers.value.indexOf(userId);

if (event.target.checked) {
  // Add the user if not already selected
  if(index === -1){
      selectedUsers.value.push(userId);
  }
} else {
  // Remove the user if selected
   if (index !== -1) {
      selectedUsers.value.splice(index, 1);
  }
}
console.log('Usuarios seleccionados:', selectedUsers.value);
};

// Función para seleccionar todos los usuarios
const selectAll = () => {
if (allSelected.value) {
  selectedUsers.value = users.value.map((user) => parseInt(user.id));
} else {
  selectedUsers.value = [];
}
console.log('Usuarios seleccionados:', selectedUsers.value);
};
// Computed para sincronizar el checkbox selectAll
allSelected.value = computed({
get: () => selectedUsers.value.length === users.value.length,
set: (val) => {
  if (val) {
    selectedUsers.value = users.value.map((user) => parseInt(user.id));
  } else {
    selectedUsers.value = [];
  }
},
});


const toggleUserStatus = async (user) => {
  try {
    if (user.habilitado === 1) {
      await props.disableUser(user.id);
      user.habilitado = 0;
    } else {
       await props.enableUser(user.id);
       user.habilitado = 1;
    }
  } catch(error){
      console.error('Error al cambiar el estado del usuario en la vista:', error);
  }
};
const editModalVisible = ref(false);
const editUserData = ref({
id:'',
name: '',
email: '',
password: '',
primer_apellido: '',
segundo_apellido: '',
rol:'',
  id_campus: null,
});
const handleEditUser = async (user) => {
  try{
      const token = localStorage.getItem('token');
    const headers = {
        Authorization: `Bearer ${token}`,
    };
     // Corrected URL to: api/v1/usuario/show/{usuario}
    const response = await axios.get(`${API_AUTH_URL}/usuario/show/${user.id}`,{headers})
      if (response.data) {
          const userData = response.data
          editUserData.value={
              id: userData.id,
              name: userData.name,
              email: userData.email,
              password: '',
              primer_apellido: userData.primer_apellido,
              segundo_apellido: userData.segundo_apellido,
              rol:userData.rol,
              id_campus: userData.id_campus,
              }
         editModalVisible.value = true;

      } else {
          console.error('Error: La respuesta de la API no tiene datos:', response);
           toast.error("Error: la respuesta del API es incorrecta");
      }


   } catch (error) {
      console.error('Error al obtener datos de usuario:', error);
      toast.error('Error al obtener datos de usuario: ' + error.message);
  }


console.log('Editing user:', user);
};
const updateUser = async () =>{
  try {
      const token = localStorage.getItem('token');
      const headers = {
          Authorization: `Bearer ${token}`,
      };
      const payload = {...editUserData.value}
      delete payload.id;
    const response = await axios.put(`${USUARIO_UPDATE_URL}/${editUserData.value.id}`,payload,{headers})
       console.log("Respuesta de la api: ", response);
       toast.success("Usuario modificado exitosamente!");
      closeEditModal();
        fetchUsers(pagination.value.current_page)
  }
  catch (error) {
       console.error('Error al actualizar usuario:', error);
      toast.error('Error al actualizar usuario: ' +  (error.response ? JSON.stringify(error.response.data) : error));
  }

}
const closeEditModal = () => {
editModalVisible.value = false;
};
const deleteSelectedUsers = async () => {
if (confirm('¿Estás seguro de que quieres eliminar los usuarios seleccionados?')) {
  try {
    const token = localStorage.getItem('token');
    const headers = {
      Authorization: `Bearer ${token}`,
    };

    // Eliminar usuarios individualmente
    for (const userId of selectedUsers.value) {
      await axios.put(`${USUARIO_DELETE_URL}/${userId}`, { habilitado: 0 }, { headers });
      console.log(`Usuario ${userId} deshabilitado`);
    }

    toast.success('Usuarios deshabilitados exitosamente.');
    selectedUsers.value = []; // Limpiar la selección
      fetchUsers(pagination.value.current_page) // Recargar la lista de usuarios
  } catch (error) {
    console.error('Error al deshabilitar los usuarios:', error);
    toast.error('Error al deshabilitar los usuarios: ' + error.message);
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
      fetchUsers(page);
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
   await fetchUsers();
});

</script>

<style lang="scss" scoped>
// Definición de colores
$white-01: rgba(255, 255, 255, 0.1);
$white-02: rgba(255, 255, 255, 0.2);
$white-03: rgba(255, 255, 255, 0.3);
$white-05: rgba(255, 255, 255, 0.5);
$white-18: rgba(255, 255, 255, 0.18);
$purple-07: rgba(96, 4, 132, 0.7);
$purple-1: rgba(96, 4, 132, 1);
$purple-focus: rgba(96, 4, 132, 0.719);
$red-07: rgba(220, 53, 69, 0.7);
$red-09: rgba(220, 53, 69, 0.9);
$green-07: rgba(40, 167, 69, 0.7);
$green-09: rgba(40, 167, 69, 0.9);
$gray-3C: #3C3C3C;
$gray-90: #9098A9;
$shadow-color: rgba(31, 38, 135, 0.37);
$text-color: #333;

// Estilos
.glassmorphic-container {
  background: $white-01;
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px 0 $shadow-color;
  border: 1px solid $white-18;
  color: $text-color;
}

.pagination {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-bottom: 20px;
  align-items: center;
}

.glassmorphic-btn {
  background: $white-02;
  backdrop-filter: blur(5px);
  border: 1px solid $white-03;
  color: $text-color;
  padding: 10px 20px;
  border-radius: 50px;
  font-weight: bold;
  transition: all 0.3s ease;
  cursor: pointer;

  &:hover {
    background: $white-03;
  }
}

.glassmorphic-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.glassmorphic-input,
.glassmorphic-select {
  background: $white-02;
  border: 1px solid $white-03;
  border-radius: 10px;
  padding: 10px;
  color: $text-color;
  width: 300px;
}

.glassmorphic-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;

  th,
  td {
    background: $white-02;
    padding: 15px;
    text-align: left;
    border: none;
  }

  th {
    font-weight: bold;
    text-transform: uppercase;
  }

  tr {
    transition: all 0.3s ease;

    &:hover {
      background: $white-03;
    }
  }
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.add-user-btn {
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
  background: $red-07;
  color: white;

  &:hover {
    background: $red-09;
  }
}

.enable-btn {
  background: $green-07;
  color: white;

  &:hover {
    background: $green-09;
  }
}

.form-title,
.list-title {
  font-size: 24px;
  margin-bottom: 20px;
  color: $text-color;
}

.radio-group {
  display: flex;
  flex-direction: row;
  gap: 20px;
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
  border: 2px solid $white-05;
  border-radius: 5px;
  background: $white-02;
  cursor: pointer;

  &:checked {
    background: $purple-07;
  }
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

  input {
    display: none;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: $gray-3C;
    transition: 0.4s;
    border-radius: 34px;

    &:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
    }

    &:after {
      content: 'DESABILITADO';
      color: white;
      display: block;
      position: absolute;
      transform: translate(-50%, -50%);
      top: 50%;
      left: 60%;
      font-size: 10px;
    }
  }

  input:checked + .slider {
    background-color: $purple-1;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px $purple-focus;
  }

  input:checked + .slider:before {
    transform: translateX(85px);
  }

  input:checked + .slider:after {
    content: 'HABILITADO';
    left: 47%;
  }
}

.edit-btn {
  margin-left: 5px;
  background: $white-02;
  backdrop-filter: blur(5px);
  border: 1px solid $white-03;
  color: $text-color;
  padding: 5px 10px;
  border-radius: 50px;
  font-weight: bold;
  transition: all 0.3s ease;
  cursor: pointer;

  &:hover {
    background: $white-03;
  }
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

  label {
    width: 150px;
    text-align: left;
  }
}

.checkbox-wrapper-47 {
  margin-right: 15px;

  input[type="radio"] {
    display: none;
    visibility: hidden;
  }

  label {
    position: relative;
    padding-left: 2em;
    padding-right: 1em;
    line-height: 2;
    cursor: pointer;
    display: inline-flex;
    align-items: center;

    &:before {
      box-sizing: border-box;
      content: " ";
      position: absolute;
      top: 0.3em;
      left: 0;
      display: block;
      width: 1.4em;
      height: 1.4em;
      border: 2px solid $gray-90;
      border-radius: 6px;
      z-index: -1;
    }
  }

  input[type=radio]:checked + label {
    padding-left: 1em;
    color: white;

    &:before {
      top: 0;
      width: 100%;
      height: 2em;
      background: $purple-1;
      border-color: $purple-1;
    }
  }

  label,
  label::before {
    transition: 0.25s all ease;
  }
}
</style> 