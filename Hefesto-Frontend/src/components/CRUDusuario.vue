<template>
    <div>
      <GlassmorphicPopup
        :visible="popupVisible"
        :title="popupTitle"
        :subtitle="popupSubtitle"
        :closeButtonText="popupCloseButtonText"
        :actionButtonText="actionButtonText"
        :closeButtonStyle="closeButtonStyle"
        :actionButtonStyle="actionButtonStyle"
        @close="closePopup"
        @action="handleAction"
      >
        <template #popup-content>
          <div v-if="currentView === 'register'">
            <h2>Registrar Usuario</h2>
            <label>
              <input type="radio" v-model="isTecnico" :value="true" /> Técnico
            </label>
            <label>
              <input type="radio" v-model="isTecnico" :value="false" /> Usuario
            </label>
            <br />

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" v-model="userData.name" class="form-control" />

            <label for="email">Email:</label>
            <input type="email" id="email" v-model="userData.email" class="form-control" />

            <label for="password">Contraseña:</label>
            <input type="password" id="password" v-model="userData.password" class="form-control" />

            <label for="primer_apellido">Primer Apellido:</label>
            <input
              type="text"
              id="primer_apellido"
              v-model="userData.primer_apellido"
              class="form-control"
            />

            <label for="segundo_apellido">Segundo Apellido:</label>
            <input
              type="text"
              id="segundo_apellido"
              v-model="userData.segundo_apellido"
              class="form-control"
            />

            <label for="campus">Campus:</label>
            <CustomSelect
              :options="campusOptions"
              :modelValue="userData.id_campus"
              @update:modelValue="handleCampusSelect"
            />

            <button class="btn btn-secondary" @click="goBack">Atrás</button>
          </div>
          <div v-else-if="currentView === 'list'">
            <h2>Lista de Usuarios</h2>
            <button class="popup-btn primary-btn add-user-btn" @click="openRegisterView">
              <img src="../assets/images/icons/usuarios.svg" alt="Añadir Usuario" style="width: 20px; margin-right: 5px;">Añadir Usuario
            </button>
            <div class="table-responsive">
              <table class="table glassmorphic-table">
                <thead>
                  <tr>
                    <th>
                      <input type="checkbox" @change="selectAll" v-model="allSelected" />
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
                  <tr v-for="user in users" :key="user.id">
                    <td>
                      <input type="checkbox" :value="user.id" @change="selectUser" v-model="selectedUsers" />
                    </td>
                    <td>
                      <img :src="API_AUTH_URL + '/' + user.foto_perfil" alt="Foto de perfil" width="50" class="rounded-image"/>
                    </td>
                    <td>{{ user.name }} {{ user.primer_apellido }} {{ user.segundo_apellido }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.rol }}</td>
                    <td>{{ getCampusName(user.id_campus) }}</td>
                    <td>
                      <button class="popup-btn danger-btn" @click="deleteUser(user.id)">Eliminar</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button class="popup-btn danger-btn" @click="deleteSelectedUsers" :disabled="selectedUsers.length === 0">Eliminar Seleccionados</button>
          </div>
        </template>
        <template #popup-actions>
          <button class="popup-btn" :class="{'default-btn': closeButtonStyle === 'default', 'cancel-btn': closeButtonStyle === 'cancel'}" @click="closePopup">
            {{ popupCloseButtonText }}
          </button>
          <button v-if="currentView === 'register'" class="popup-btn" :class="{'default-btn': actionButtonStyle === 'default', 'primary-btn': actionButtonStyle === 'primary'}" @click="handleAction">
            {{ popupActionButtonText }}
          </button>
        </template>
      </GlassmorphicPopup>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import CustomSelect from './CustomSelect.vue';
import GlassmorphicPopup from './GlassmorphicPopup.vue';
import axios from 'axios';

const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const REGISTER_URL = `${API_AUTH_URL}/auth/register`;
const REGISTER_TECNICO_URL = `${API_AUTH_URL}/auth/register_tecnico`;
const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
const USUARIO_ALL_URL = `${API_AUTH_URL}/usuario/all`;
const USUARIO_DELETE_URL = `${API_AUTH_URL}/usuario/delete`;

const popupVisible = ref(false);
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
const currentView = ref('options');
const users = ref([]); // Para almacenar los usuarios
const selectedUsers = ref([]); // Para almacenar los usuarios seleccionados
const allSelected = ref(false); //Para almacenar todos los usuarios

const emit = defineEmits(['close']);

const openPopup = () => {
    popupVisible.value = true;
};

const closePopup = () => {
    popupVisible.value = false;
    emit('close');
};

const openRegisterView = () => {
    currentView.value = 'register';
    popupActionButtonText.value = 'Registrar';
};

const goBack = () => {
    currentView.value = 'list';
    popupActionButtonText.value = null;
};

const handleCampusSelect = (campusId) => {
    userData.value.id_campus = campusId;
};

const handleAction = async () => {
    try {
        const url = isTecnico.value ? REGISTER_TECNICO_URL : REGISTER_URL;

        if (!userData.value.id_campus) {
            alert('El campo Campus es obligatorio.');
            return;
        }

        const payload = {
            name: userData.value.name,
            email: userData.value.email,
            password: userData.value.password,
            id_campus: Number(userData.value.id_campus),
        };

        if (userData.value.primer_apellido) {
            payload.primer_apellido = userData.value.primer_apellido;
        }

        if (userData.value.segundo_apellido) {
            payload.segundo_apellido = userData.value.segundo_apellido;
        }

        const token = localStorage.getItem('token');
        const headers = {
            Authorization: `Bearer ${token}`,
        };

        const response = await axios.post(url, payload, { headers });
        console.log('Registro exitoso:', response.data);
        alert('Usuario registrado exitosamente!');

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
        alert('Error al registrar usuario: ' + (error.response ? JSON.stringify(error.response.data) : error));
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

        console.log('Realizando petición a:', USUARIO_ALL_URL);
        console.log('Headers de la petición:', headers);

        const response = await axios.get(USUARIO_ALL_URL, { headers });
        console.log('Respuesta de la API:', response.data); // Imprime la respuesta completa

        users.value = response.data.data; // Almacena los usuarios en el ref users
        currentView.value = 'list'; // Cambia a la vista de lista
    } catch (error) {
        console.error('Error al obtener la lista de usuarios:', error);
        alert('Error al obtener la lista de usuarios: ' + error.message);
    }
};

// Función para seleccionar un usuario
const selectUser = (event) => {
    const userId = parseInt(event.target.value);
    if (event.target.checked) {
        selectedUsers.value.push(userId);
    } else {
        const index = selectedUsers.value.indexOf(userId);
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

// Función para eliminar un usuario
const deleteUser = async (userId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        try {
            const token = localStorage.getItem('token');
            const headers = {
                Authorization: `Bearer ${token}`,
            };
            const response = await axios.delete(`${USUARIO_DELETE_URL}/${userId}`, { headers });
            console.log('Usuario eliminado:', response.data);
            alert('Usuario eliminado exitosamente.');
            fetchUsers(); // Recargar la lista de usuarios
        } catch (error) {
            console.error('Error al eliminar el usuario:', error);
            alert('Error al eliminar el usuario: ' + error.message);
        }
    }
};

// Función para eliminar los usuarios seleccionados
const deleteSelectedUsers = async () => {
    if (confirm('¿Estás seguro de que quieres eliminar los usuarios seleccionados?')) {
        try {
            const token = localStorage.getItem('token');
            const headers = {
                Authorization: `Bearer ${token}`,
            };

            // Eliminar usuarios individualmente
            for (const userId of selectedUsers.value) {
                await axios.delete(`${USUARIO_DELETE_URL}/${userId}`, { headers });
                console.log(`Usuario ${userId} eliminado`);
            }

            alert('Usuarios eliminados exitosamente.');
            selectedUsers.value = []; // Limpiar la selección
            fetchUsers(); // Recargar la lista de usuarios
        } catch (error) {
            console.error('Error al eliminar los usuarios:', error);
            alert('Error al eliminar los usuarios: ' + error.message);
        }
    }
};

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
        alert('Error al obtener campus: ' + error.message);
    }
    currentView.value = 'list'; // Mostrar la lista por defecto
    await fetchUsers(); // Cargar la lista de usuarios al montar el componente
});

defineExpose({
    openPopup,
});

const closeButtonStyle = ref('default');
const actionButtonStyle = ref('primary');
const actionButtonText = ref(null); // Inicialmente no hay botón de acción
</script>

<style scoped>
/* Estilos para la tabla */
.table-responsive {
  overflow-x: auto; /* Agrega scroll horizontal si es necesario */
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.3);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: #333;
}
.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background-color: transparent;
}

.table th,
.table td {
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding: 12px;
  text-align: left;
  color: #333; /* Cambiar el color a un gris más oscuro */
}

.table th {
  font-weight: 600;
  text-transform: uppercase; /* Convertir el encabezado en mayúsculas */
  font-size: 0.9rem; /* Reducir el tamaño de la fuente */
}

.table tbody tr {
  transition: background-color 0.3s ease;
}

.table tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Reduce padding en las celdas de la tabla */
.table th,
.table td {
  padding: 0.75rem;
}

.popup-btn {
  padding: 0.8rem 1.8rem;
  border-radius: 2rem;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
}

.default-btn {
  background: rgba(255, 255, 255, 0.3);
  color: #333;
}

.default-btn:hover {
  background: rgba(255, 255, 255, 0.5);
}
/* Estilos para botones morados */
.primary-btn {
  background: rgba(96, 4, 132, 1); /* Morado */
  color: white;
}

.primary-btn:hover {
  background: rgba(96, 4, 132, 0.9);
}

.add-user-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.danger-btn {
    background: rgba(220, 53, 69, 0.7);
    color: white;
}

.danger-btn:hover {
    background: rgba(220, 53, 69, 0.9);
}
.glassmorphic-table {
  border-radius: 1rem; /* Bordes redondeados */
  background: rgba(255, 255, 255, 0.3); /* Fondo transparente */
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Sombra sutil */
  backdrop-filter: blur(5px); /* Efecto de desenfoque */
  -webkit-backdrop-filter: blur(5px); /* Para compatibilidad con Safari */
  border: 1px solid rgba(255, 255, 255, 0.3); /* Borde sutil */
  color: #333;
}
/* Estilos para alinear la imagen de perfil y redondear */
.rounded-image {
    border-radius: 50%; /* Redondea la imagen */
    vertical-align: middle; /* Alinea verticalmente con el texto */
    margin-right: 8px; /* Espacio entre la imagen y el texto */
}
</style>