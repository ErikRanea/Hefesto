<template>
  <div class="glassmorphic-container">
      <button v-if="currentView === 'list'" class="glassmorphic-btn" @click="goBackToList">Volver</button>
      <div v-if="currentView === 'register'" class="glassmorphic-form">
          <h2 class="form-title">Registrar Usuario</h2>
          <div class="radio-group">
              <label class="radio-label">
                  <input type="radio" v-model="isTecnico" :value="true" />
                  <span>Técnico</span>
              </label>
              <label class="radio-label">
                  <input type="radio" v-model="isTecnico" :value="false" />
                  <span>Usuario</span>
              </label>
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

          <button class="glassmorphic-btn" @click="goBack">Volver</button>
      </div>
      <div v-else-if="currentView === 'list'" class="user-list">
          <h2 class="list-title">Lista de Usuarios</h2>
          <button class="glassmorphic-btn add-user-btn" @click="openRegisterView">
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
          <button class="glassmorphic-btn danger-btn" @click="deleteSelectedUsers" :disabled="selectedUsers.length === 0">Deshabilitar Seleccionados</button>
      </div>
    <teleport to="body">
       <div v-if="editModalVisible" class="edit-modal">
          <div class="modal-content">
          <h2 class="form-title">Editar Usuario</h2>
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
                      <option value="administrador">Administrador</option>
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
              <button class="glassmorphic-btn" @click="closeEditModal">Cancelar</button>
              <button class="glassmorphic-btn" @click="updateUser">Guardar</button>
           </div>
       </div>
   </teleport>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import CustomSelect from './CustomSelect.vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';


const toast = useToast();
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const REGISTER_URL = `${API_AUTH_URL}/auth/register`;
const REGISTER_TECNICO_URL = `${API_AUTH_URL}/auth/register_tecnico`;
const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
const USUARIO_ALL_URL = `${API_AUTH_URL}/usuario/all`;
const USUARIO_DELETE_URL = `${API_AUTH_URL}/usuario/update`;
const USUARIO_UPDATE_URL = `${API_AUTH_URL}/usuario/update`;

const popupVisible = ref(true);
const popupTitle = ref('Gestión de Usuarios');
const popupSubtitle = ref('');
const popupCloseButtonText = ref('Cerrar');
const popupActionButtonText = ref('Registrar');

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
popupActionButtonText.value = 'Registrar';
};

const goBack = () => {
  currentView.value = 'list';
  popupActionButtonText.value = null;
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
const fetchUsers = async () => {
try {
  const token = localStorage.getItem('token');
  const headers = {
    Authorization: `Bearer ${token}`,
  };
  const response = await axios.get(USUARIO_ALL_URL, { headers });
  users.value = response.data.data;
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
      await fetchUsers()
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
    await fetchUsers(); // Recargar la lista de usuarios
  } catch (error) {
    console.error('Error al deshabilitar los usuarios:', error);
    toast.error('Error al deshabilitar los usuarios: ' + error.message);
  }
}
};
const handleFilterCampusSelect = (campusId) =>{
  selectedCampus.value = campusId;
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
const closeButtonStyle = ref('default');
const actionButtonStyle = ref('primary');
const actionButtonText = ref(null); // Inicialmente no hay botón de acción
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

.glassmorphic-input, .glassmorphic-select {
background: rgba(255, 255, 255, 0.2);
border: 1px solid rgba(255, 255, 255, 0.3);
border-radius: 10px;
padding: 10px;
color: #333;
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
display: flex;
gap: 20px;
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
.edit-modal{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content{
   background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #333;
   display: flex;
  flex-direction: column;
  gap: 20px;
  min-width: 400px;
}
</style>