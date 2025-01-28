<template>
  <div class="row g-4 mb-4">
    <!-- Botones de creación y asignación (solo para admin) -->
    <div v-if="isAdmin" class="col-sm-6 col-md-3">
      <div
        class="card glassmorphic-card colored-shadow-pending create-incidencia-card"
        @click="openCreateMantenimientoPopup"
      >
        <div
          class="card-bodytotal card-body-same-height d-flex justify-content-between align-items-center"
        >
          <h6 class="mb-0">Crear Mantenimiento</h6>
          <img src="../assets/images/icons/crear.svg" alt="Crear Mantenimiento" />
        </div>
      </div>
    </div>
    <div v-if="isAdmin" class="col-sm-6 col-md-3">
      <div
        class="card glassmorphic-card colored-shadow-pending create-incidencia-card"
        @click="openAsignarMantenimientoPopup"
      >
        <div
          class="card-bodytotal card-body-same-height d-flex justify-content-between align-items-center"
        >
          <h6 class="mb-0">Asignar a Máquina</h6>
          <img src="../assets/images/icons/asignar.svg" alt="Asignar a Máquina" />
        </div>
      </div>
    </div>

    <!-- Lista de mantenimientos -->
    <div class="col-12">
      <div class="incidencia-list glassmorphic-card">
        <div class="incidencia-list-header">
          <div>Mantenimientos</div>
          <div class="boton-barra-box">
            <img class="img-fluid mb-3 boton-barra huge-icon" src="../assets/images/icons/filtro.svg" alt="Filtrar">
          </div>
          <div class="priority-legend">
            <span><span class="priority-dot alta"></span> Alta</span>
            <span><span class="priority-dot media"></span> Media</span>
            <span><span class="priority-dot baja"></span> Baja</span>
          </div>
        </div>
        <div v-if="loading">Cargando mantenimientos...</div>
        <div v-else-if="error">Error al cargar los mantenimientos: {{ error.message }}</div>
        <div v-else class="incidencias-container">
          <div
            v-for="incidencia in incidencias"
            :key="incidencia.date + incidencia.time"
            class="incidencia-item"
            @click="handleIncidenciaClick(incidencia)"
          >
            <div class="priority-marker" :class="incidencia.priority"></div>
            <div class="incidencia-content">
              <div class="incidencia-date">
                <span>{{ incidencia.date }}</span>
                <span>{{ incidencia.time }}</span>
              </div>
              <div class="incidencia-text">
                <div>{{ incidencia.titulo }}</div>
                <small class="text-muted">{{ incidencia.subtitulo }}</small>
              </div>
              <div class="incidencia-status-box">
                <span
                  class="incidencia-status"
                  :class="incidencia.status.toLowerCase().replace(' ', '-')"
                >
                  {{ incidencia.status }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Popups -->
    <GlassmorphicPopup
      :visible="showCreateMantenimientoPopup"
      title="Crear Mantenimiento Preventivo"
      closeButtonText="Cancelar"
      actionButtonText="Crear Mantenimiento"
      @close="closeCreateMantenimientoPopup"
      @action="handleCreateMantenimiento"
    >
      <template #popup-content>
        <div class="form-group mb-3">
          <label for="nombre">Nombre:</label>
          <CustomInput placeholder="Ingrese el nombre" v-model="newMantenimiento.nombre" />
        </div>
        <div class="form-group mb-3">
          <label for="descripcion">Descripción:</label>
          <CustomInput
            placeholder="Ingrese la descripción"
            v-model="newMantenimiento.descripcion"
          />
        </div>
        <div class="form-group mb-3">
          <label for="periodicidad">Periodicidad (días):</label>
          <CustomInput
            type="number"
            placeholder="Ingrese la periodicidad"
            v-model="newMantenimiento.periodicidad"
          />
        </div>
      </template>
    </GlassmorphicPopup>

    <GlassmorphicPopup
      :visible="showAsignarMantenimientoPopup"
      title="Asignar Mantenimiento a Máquina"
      closeButtonText="Cancelar"
      actionButtonText="Asignar"
      @close="closeAsignarMantenimientoPopup"
      @action="handleAsignarMantenimiento"
    >
      <template #popup-content>
        <div class="form-group mb-3">
          <label for="mantenimiento">Mantenimiento:</label>
          <CustomSelect
            :options="mantenimientosOptions"
            v-model="asignarMantenimiento.id_mantenimiento"
          />
        </div>
        <div class="form-group mb-3">
          <label for="maquina">Máquina:</label>
          <CustomSelect
            :options="maquinasOptions"
            v-model="asignarMantenimiento.id_maquina"
          />
        </div>
      </template>
    </GlassmorphicPopup>

    <!-- Resto de los popups existentes -->
    <GlassmorphicPopup
      :visible="showIncidenciaDetailsPopup"
      title="Detalles de la Incidencia"
      closeButtonText="Cerrar"
      @close="closeIncidenciaDetailsPopup"
    >
      <template #popup-content>
        <div v-if="selectedIncidencia">
          <p><strong>Título:</strong> {{ selectedIncidencia.titulo }}</p>
          <p><strong>Subtítulo:</strong> {{ selectedIncidencia.subtitulo }}</p>
          <p><strong>Fecha:</strong> {{ selectedIncidencia.date }}</p>
          <p><strong>Hora:</strong> {{ selectedIncidencia.time }}</p>
          <p><strong>Estado:</strong> {{ selectedIncidencia.status }}</p>

          <div v-if="!isOperario">
            <label for="comment">Comentario:</label>
            <textarea
              id="comment"
              v-model="commentText"
              class="form-control custom-textarea"
              rows="3"
              placeholder="Agrega un comentario"
              @input="handleCommentChange"
            ></textarea>
          </div>
          <div v-else-if="selectedIncidencia.descripcion">
            <p><strong>Comentario:</strong> {{ selectedIncidencia.descripcion }}</p>
          </div>
          <div v-else>
            <p>No hay comentarios para mostrar</p>
          </div>
          <div class="action-buttons mt-auto">
            <button
              v-if="isTecnicoOrAdmin && selectedIncidencia.status !== 'En curso' && !hasReclamada"
              class="popup-btn primary"
              @click="handleReclamarIncidencia"
              :disabled="reclamandoIncidencia"
            >
              {{ reclamandoIncidencia ? 'Reclamando...' : 'Reclamar Incidencia' }}
            </button>
            <div v-if="isTecnicoOrAdmin && selectedIncidencia.status === 'En curso'" class="d-flex justify-content-center gap-2">
              <button class="popup-btn1 cancel-btn" @click="openMotivoSalidaPopup">Salir de Incidencia</button>
              <button class="popup-btn primary" @click="openMotivoCierrePopup">Cerrar Incidencia</button>
            </div>
          </div>
        </div>
        <div v-else>
          <p>No hay detalles de incidencia para mostrar</p>
        </div>
      </template>
    </GlassmorphicPopup>
    <GlassmorphicPopup
          :visible="showErrorPopup"
          title="Error"
          closeButtonText="Cerrar"
          @close="closeErrorPopup"
      >
          <template #popup-content>
              <p>{{ errorMessage }}</p>
          </template>
      </GlassmorphicPopup>
     <GlassmorphicPopup
      :visible="showMotivoCierrePopup"
      title="Motivo de Cierre de Incidencia"
      closeButtonText="Cancelar"
      actionButtonText="Confirmar Cierre"
      @close="closeMotivoCierrePopup"
      @action="handleCerrarIncidencia"
    >
      <template #popup-content>
        <div class="form-group mb-3">
          <label for="motivoCierre">Motivo:</label>
          <textarea id="motivoCierre" v-model="motivoCierre" class="form-control custom-textarea" rows="3"
            placeholder="Indique el motivo del cierre"></textarea>
        </div>
      </template>
    </GlassmorphicPopup>
     <GlassmorphicPopup
      :visible="showMotivoSalidaPopup"
      title="Motivo de Salida de Incidencia"
      closeButtonText="Cancelar"
      actionButtonText="Confirmar Salida"
      @close="closeMotivoSalidaPopup"
      @action="handleSalirIncidencia"
    >
      <template #popup-content>
        <div class="form-group mb-3">
          <label for="motivoSalida">Motivo:</label>
          <textarea id="motivoSalida" v-model="motivoSalida" class="form-control custom-textarea" rows="3"
            placeholder="Indique el motivo de su salida"></textarea>
        </div>
      </template>
    </GlassmorphicPopup>
  </div>
</template>
<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import GlassmorphicPopup from './GlassmorphicPopup.vue';
import CustomSelect from './CustomSelect.vue';
import CustomInput from './CustomInput.vue';
import { useToast } from "vue-toastification";

const loading = ref(true);
const toast = useToast();
const error = ref(null);
const incidencias = ref([]);
const tiposIncidencia = ref([]);
const maquinas = ref([]);
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const ALL_INCIDENCIAS_URL = `${API_AUTH_URL}/incidencia/all_mantenimientos`;
const ALL_TIPO_INCIDENCIAS_URL = `${API_AUTH_URL}/tipo_incidencia/all`;
const ALL_MAQUINAS_URL = `${API_AUTH_URL}/maquina/all`;
const ME_URL = `${API_AUTH_URL}/auth/me`;
const userPicture = localStorage.getItem('picture');
const userId = localStorage.getItem('id');
const userRole = ref(null);
const isTecnico = computed(() => userRole.value === 'tecnico');
const isAdmin = computed(() => userRole.value === 'administrador');
const isOperario = computed(() => userRole.value === 'operario');
const isTecnicoOrAdmin = computed(() => isTecnico.value || isAdmin.value);
const userImagePath = computed(() => {
    return userPicture ? `${IMAGE_URL}${userPicture}` : null;
});
const showMotivoSalidaPopup = ref(false);
const motivoSalida = ref('');
const showCreateMantenimientoPopup = ref(false);
const showAsignarMantenimientoPopup = ref(false);
const guardandoComentario = ref(false);
const newMantenimiento = ref({
    nombre: '',
    descripcion: '',
    periodicidad: null,
});
const asignarMantenimiento = ref({
    id_mantenimiento: null,
    id_maquina: null,
});
const reclamandoIncidencia = ref(false);
const showMotivoCierrePopup = ref(false);
const motivoCierre = ref('');
const debouncedUpdateDescription = ref(null);
const hasReclamada = ref(false);
const showErrorPopup = ref(false);
const errorMessage = ref('');
const mantenimientosOptions = ref([]);
const maquinasOptions = ref([]);

const obtenerPrioridad = (prioridad) => {
    if (prioridad === "alta" || prioridad === "media" || prioridad === "baja") {
        return prioridad;
    } else {
        return "baja";
    }
};

const obtenerEstado = (estado) => {
    switch (estado) {
        case 0:
            return 'Nueva';
        case 1:
            return 'Pendiente';
        case 2:
            return 'En curso';
        case 3:
            return 'Cerrada';
        case 4:
            return 'Mantenimiento';
        default:
            return '';
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const options = {year: 'numeric', month: 'long', day: 'numeric'};
    return date.toLocaleDateString(undefined, options);
};

const formatTime = (dateString) => {
    const date = new Date(dateString);
    const options = {hour: '2-digit', minute: '2-digit'};
    return date.toLocaleTimeString(undefined, options);
};

const fetchData = async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        throw new Error('No token found');
    }
    try {
        const response = await axios.post(
            ALL_INCIDENCIAS_URL,
            {},
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );
        return response.data;
    } catch (err) {
        console.error('Error al obtener los datos de las incidencias:', err);
        error.value = {
            message: 'Error al obtener los datos de las incidencias',
            type: 'api',
        };
        throw new Error('Error al obtener los datos de las incidencias');
    }
};

const fetchTipoIncidencias = async () => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            throw new Error('No token found');
        }
        const response = await axios.get(ALL_TIPO_INCIDENCIAS_URL, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        return response.data;
    } catch (error) {
        console.error('Error al obtener los tipos de incidencia:', error);
        error.value = {
            message: 'Error al obtener los tipos de incidencia',
            type: 'api',
        };
        throw new Error('Error al obtener los tipos de incidencia');
    }
};

const fetchMaquinas = async () => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            throw new Error('No token found');
        }
        const response = await axios.post(ALL_MAQUINAS_URL, {}, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.data && Array.isArray(response.data.data)) {
            return response.data.data;
        } else {
            return [];
        }
    } catch (error) {
        console.error('Error al obtener las maquinas:', error);
        error.value = {
            message: 'Error al obtener las maquinas',
            type: 'api',
        };
        throw new Error('Error al obtener las maquinas');
    }
};

const loadIncidencias = async () => {
    try {
        loading.value = true;
        const incidenciasData = await fetchData();

        incidencias.value = incidenciasData.map((incidencia) => ({
            ...incidencia,
            id: incidencia.id,
            priority: obtenerPrioridad(incidencia.prioridad),
            status: obtenerEstado(incidencia.estado),
            date: formatDate(incidencia.fecha_apertura),
            time: formatTime(incidencia.fecha_apertura),
            descripcion: incidencia.descripcion,
            id_tecnico: incidencia.id_mantenimiento
        }));
    } catch (err) {
        error.value = err;
    } finally {
        loading.value = false;
    }
};

const fetchUserData = async () => {
    const token = localStorage.getItem('token');
    if (token) {
        try {
            const response = await axios.get(ME_URL, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            userRole.value = response.data.rol;
        } catch (error) {
            console.error('Error fetching user data:', error);
            error.value = {
                message: 'Error al obtener la información del usuario',
                type: 'api',
            };
        }
    }
};

const checkUserHasReclamada = () => {
    if (isTecnico.value || isAdmin.value) {
        hasReclamada.value = incidencias.value.some(incidencia => incidencia.id_tecnico === Number(userId) && incidencia.status === 'En curso');
    }
};
const fetchMantenimientosOptions = async () => {
  try {
      const mantenimientosData = await fetchMantenimientos();
      console.log('Mantenimientos options:', mantenimientosData);
      mantenimientosOptions.value = mantenimientosData.map((mantenimiento) => ({
          id: mantenimiento.id,
          label: mantenimiento.nombre, 
      }));
  } catch (error) {
      console.error('Error al obtener los mantenimientos para las opciones:', error);
      error.value = {
          message: 'Error al obtener los mantenimientos para las opciones',
          type: 'api',
      };
  }
};
onMounted(async () => {
  try {
      await fetchUserData();
      const [tiposData, maquinasData] = await Promise.all([
          fetchTipoIncidencias(),
          fetchMaquinas(),
      ]);

      tiposIncidencia.value = tiposData.map((tipo) => ({
          id: tipo.id,
          label: tipo.tipo,
      }));

      maquinas.value = maquinasData.map((maquina) => ({
          id: maquina.id,
          label: maquina.nombre_maquina,
      }));

      await fetchMantenimientosOptions(); // Asegúrate de que esta línea esté presente
      maquinasOptions.value = maquinasData.map((maquina) => ({
          id: maquina.id,
          label: maquina.nombre_maquina,
      }));
      await loadIncidencias();
      checkUserHasReclamada();
  } catch (err) {
      error.value = err;
      loading.value = false;
  }
});

watch(incidencias, () => {
    checkUserHasReclamada();
});

const openCreateMantenimientoPopup = () => {
    showCreateMantenimientoPopup.value = true;
};

const closeCreateMantenimientoPopup = () => {
    closePopup(showCreateMantenimientoPopup);
    newMantenimiento.value = {
        nombre: '',
        descripcion: '',
        periodicidad: null,
    };
};

const handleCreateMantenimiento = async () => {
    const token = localStorage.getItem('token');
    if (!token) {
        throw new Error('No token found');
    }
    try {
        const apiUrl = `${API_AUTH_URL}/mantenimiento_preventivo/store`;
        const response = await axios.post(apiUrl, newMantenimiento.value, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.status === 201) {
            closeCreateMantenimientoPopup();
            await fetchMantenimientosOptions();
            await loadIncidencias();
        }
    } catch (error) {
        console.error('Error al crear el mantenimiento:', error);
        error.value = {
            message: 'Error al crear el mantenimiento',
            type: 'api',
        };
    }
};

const openAsignarMantenimientoPopup = () => {
    showAsignarMantenimientoPopup.value = true;
};

const closeAsignarMantenimientoPopup = () => {
    closePopup(showAsignarMantenimientoPopup);
    asignarMantenimiento.value = {
        id_mantenimiento: null,
        id_maquina: null,
    };
};

const handleAsignarMantenimiento = async () => {
  const token = localStorage.getItem('token');
  if (!token) {
      throw new Error('No token found');
  }

  // Validar que se hayan seleccionado tanto el mantenimiento como la máquina
  if (!asignarMantenimiento.value.id_mantenimiento || !asignarMantenimiento.value.id_maquina) {
      toast.error("Por favor seleccione tanto el mantenimiento como la máquina", {
          timeout: 3000
      });
      return;
  }

  try {
      const apiUrl = `${API_AUTH_URL}/mantenimiento_maquina/asignar_mantenimiento`;
      
      // Imprimir los datos que se van a enviar para debug
      console.log('Datos a enviar:', asignarMantenimiento.value);

      const response = await axios.post(apiUrl, {
          id_mantenimiento: asignarMantenimiento.value.id_mantenimiento,
          id_maquina: asignarMantenimiento.value.id_maquina
      }, {
          headers: {
              Authorization: `Bearer ${token}`,
          },
      });

      // Imprimir la respuesta para debug
      console.log('Respuesta:', response);

      if (response.data.success || response.status === 200) {
          toast.success("Mantenimiento asignado correctamente", {
              timeout: 3000
          });
          
          closeAsignarMantenimientoPopup();
          await loadIncidencias();
      } else {
          throw new Error(response.data.message || 'Error al asignar el mantenimiento');
      }
  } catch (error) {
      console.error('Error al asignar el mantenimiento:', error);
      toast.error(error.response?.data?.message || "Error al asignar el mantenimiento", {
          timeout: 3000
      });
      error.value = {
          message: 'Error al asignar el mantenimiento',
          type: 'api',
      };
  }
};
const showIncidenciaDetailsPopup = ref(false);
const selectedIncidencia = ref(null);
const commentText = ref('');

const handleIncidenciaClick = (incidencia) => {
    selectedIncidencia.value = incidencia;
    commentText.value = incidencia.descripcion || '';
    showIncidenciaDetailsPopup.value = true;
};
const closePopup = (popupRef) => {
    popupRef.value = false;
};
const closeIncidenciaDetailsPopup = () => {
    closePopup(showIncidenciaDetailsPopup);
    selectedIncidencia.value = null;
    commentText.value = '';
    motivoSalida.value = '';
    motivoCierre.value = '';
};
const handleCommentChange = () => {
    if (debouncedUpdateDescription.value) {
        clearTimeout(debouncedUpdateDescription.value);
    }

    debouncedUpdateDescription.value = setTimeout(async () => {
        await handleUpdateDescription();
    }, 1000);
};

const handleUpdateDescription = async () => {
    if (!selectedIncidencia.value) return;
    guardandoComentario.value = true;
    const token = localStorage.getItem('token');
    if (!token) {
        throw new Error('No token found');
    }
    try {
        const apiUrl = `${API_AUTH_URL}/incidencia/update_description/${selectedIncidencia.value.id}`;
        const requestData = {
            descripcion: commentText.value,
        };

        const response = await axios.put(apiUrl,requestData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        if (response.status === 200) {
            selectedIncidencia.value.descripcion = commentText.value;
        }
    } catch (error) {
        console.error("Error al actualizar el comentario:", error);
          error.value = {
                message: 'Error al actualizar el comentario.',
                type: 'api',
            };
    }finally{
        guardandoComentario.value = false;
    }
};
const handleReclamarIncidencia = async () => {
    if(!selectedIncidencia.value) return;
    reclamandoIncidencia.value = true;
    const token = localStorage.getItem('token');
    if(!token){
        throw new Error('No token found');
    }
    try{
        const apiUrl = `${API_AUTH_URL}/tecnico_incidencia/reclamar_incidencia`;
        const requestData = {
            id_incidencia: selectedIncidencia.value.id
        };

        const response = await axios.post(apiUrl,requestData,{
            headers:{
                Authorization: `Bearer ${token}`
            }
        });
        if(response.status === 200 || response.status === 201){
            console.log('Incidencia reclamada con éxito');
            await loadIncidencias();
            selectedIncidencia.value.status = 'En curso';
        }
        else {
            if (response.status === 400) {
                errorMessage.value = 'No puedes reclamar más de una incidencia a la vez.';
                showErrorPopup.value = true;
            } else {
                console.error('Error al reclamar la incidencia, respuesta no exitosa', response);
                errorMessage.value = "Error al reclamar la incidencia: " + (response.data.message || "Compruebe la consola para más información");
                showErrorPopup.value = true;
            }
        }
    }catch(error){
        if (error.message.includes('400')) {
            errorMessage.value = 'No puedes reclamar más de una incidencia a la vez.';
            showErrorPopup.value = true;
        } else{
            console.error('Error al reclamar la incidencia:', error);
            errorMessage.value = "Error al reclamar la incidencia: " + (error.message || "Compruebe la consola para más información");
            showErrorPopup.value = true;
        }
    }finally{
        reclamandoIncidencia.value = false;
    }
};
const closeErrorPopup = () =>{
    showErrorPopup.value = false;
    errorMessage.value = '';
};
const openMotivoSalidaPopup = () => {
    showMotivoSalidaPopup.value = true;
};

const closeMotivoSalidaPopup = () => {
    closePopup(showMotivoSalidaPopup);
    motivoSalida.value = '';
};
const handleSalirIncidencia = async () => {
    if(!selectedIncidencia.value) return;
    const token = localStorage.getItem('token');
    if(!token){
        throw new Error('No token found');
    }
    try{
        const apiUrl = `${API_AUTH_URL}/tecnico_incidencia/salir_incidencia`;
        const requestData = {
            id_incidencia: selectedIncidencia.value.id,
            motivo_salida: motivoSalida.value
        };

        const response = await axios.put(apiUrl,requestData,{
            headers:{
                Authorization: `Bearer ${token}`
            }
        });
        if(response.status === 200){
            closeMotivoSalidaPopup();
            closeIncidenciaDetailsPopup();
            await loadIncidencias();
        }
    }catch (error){
        console.error('Error al salir de la incidencia:',error);
          error.value = {
                message: 'Error al salir de la incidencia.',
                type: 'api',
            };
    }
};
const openMotivoCierrePopup = () => {
    showMotivoCierrePopup.value = true;
};

const closeMotivoCierrePopup = () => {
    closePopup(showMotivoCierrePopup);
    motivoCierre.value = '';
};
const handleCerrarIncidencia = async () => {
    if(!selectedIncidencia.value) return;
    const token = localStorage.getItem('token');
    if(!token){
        throw new Error('No token found');
    }
    try{
        const apiUrl = `${API_AUTH_URL}/tecnico_incidencia/cerrar_incidencia`;
        const requestData = {
            id_incidencia: selectedIncidencia.value.id,
            motivo_salida: motivoCierre.value,
        };
        const response = await axios.put(apiUrl,requestData,{
            headers:{
                Authorization: `Bearer ${token}`
            }
        });
        if(response.status === 200){
            await loadIncidencias();
            selectedIncidencia.value.status = 'Cerrada';
            closeMotivoCierrePopup();
            closeIncidenciaDetailsPopup();
        }
    }catch (error){
        console.error('Error al cerrar la incidencia', error);
            error.value = {
                message: 'Error al cerrar la incidencia.',
                type: 'api',
            };
    }
};

const fetchMantenimientos = async () => {
  try {
      const token = localStorage.getItem('token');
      if (!token) {
          throw new Error('No token found');
      }
      const response = await axios.get(`${API_AUTH_URL}/mantenimiento_preventivo/all`, {
          headers: {
              Authorization: `Bearer ${token}`,
          },
      });

      console.log('Mantenimientos:', response.data);

      if (response.data && Array.isArray(response.data.data)) {
          return response.data.data; 
      } else {
          return [];
      }
  } catch (error) {
      console.error('Error al obtener los mantenimientos:', error);
          error.value = {
          message: 'Error al obtener los mantenimientos',
          type: 'api',
      };
      throw new Error('Error al obtener los mantenimientos');
  }
};

</script>
  <style lang="scss" scoped>
$color-white: #fff;
$color-light-gray: #f8f8f8;
$color-gray: #ddd;
$color-dark-gray: #666;
$color-shadow: rgba(0, 0, 0, 0.1);
$color-shadow-hover: rgba(0, 0, 0, 0.2);
$color-high-priority: #FF5252;
$color-medium-priority: #FFCA28;
$color-low-priority: #4CAF50;
$color-new: #B89B00;
$color-pending: #B89B00;
$color-maintenance: #000000;
$color-closed: #000000;
$color-in-progress: #600484;
$color-primary: rgba(0, 123, 255, 0.9);
$color-danger: rgba(255, 0, 0, 0.8);
$color-text:#333;

.create-incidencia-card {
  cursor: pointer;
  transition: all 0.3s;

  &:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.7);
  }
}

.card-body-same-height {
  min-height: 100px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.glassmorphic-card {
  background: rgba($color-white, 0.7) !important;
  backdrop-filter: blur(8px);
  border: 1px solid rgba($color-white, 0.05) !important;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
}

.incidencia-panel {
  width: 100%;
  padding: 20px;
  background-color: $color-light-gray;
  border-radius: 10px;
  box-shadow: 0 2px 4px $color-shadow;
}

.incidencia-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.create-incidencia-btn {
  background-color: $color-white;
  border: 1px solid $color-gray;
  border-radius: 5px;
  padding: 12px 20px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;

  &:hover {
    background-color: #f0f0f0;
  }
}

.boton-barra-box {
  display: flex;
  align-items: center;
}

.boton-barra {
  padding: 6px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.plus-icon {
  font-size: 1.5rem;
  margin-top: -4px;
}

.incidencia-stats {
  display: flex;
  gap: 10px;
}

.incidencia-stat-box {
  background-color: $color-white;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 15px;
  border-radius: 5px;
  min-width: 140px;
  gap: 5px;
  box-shadow: 0 2px 4px $color-shadow;

  &.active {
    background-color: #e3fae8;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  }
}

.title {
  font-size: 0.8rem;
}

.count {
  font-size: 1.4rem;
  font-weight: bold;
}

.icon {
  font-size: 1.2rem;
}

.incidencia-list {
  background-color: rgba($color-white, 0.7) !important;
  border-radius: 5px;
  padding: 20px;
  margin-top: 20px;
}

.incidencia-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
  padding: 0 10px 15px 10px;
  margin-bottom: 15px;
  font-size: 0.8rem;
  color: $color-dark-gray;
}

.priority-legend {
  display: flex;
  gap: 20px;
}

.priority-dot {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-right: 2px;
}

.alta {
  background-color: $color-high-priority;
}

.media {
  background-color: $color-medium-priority;
}

.baja {
  background-color: $color-low-priority;
}

.incidencia-item {
  display: flex;
  padding: 15px;
  margin-bottom: 10px;
  border-radius: 8px;
  transition: all 0.2s ease;
  gap: 15px;
  align-items: flex-start;
  cursor: pointer;

  &:hover {
    background-color: rgba($color-white, 0.1);
  }
}

.incidencia-date {
  display: flex;
  flex-direction: column;
  font-size: 0.8rem;
  color: $color-dark-gray;
  min-width: 100px;
  flex-shrink: 0;

  span:first-child {
    font-weight: 500;
  }
}

.incidencia-content {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  flex: 1;
}

.priority-marker {
  width: 5px;
  height: 40px;
  border-radius: 2px;
  flex-shrink: 0;
  margin-right: 5px;
}

.incidencia-text {
  display: flex;
  flex-direction: column;
  font-size: 0.9rem;
  line-height: 1.3;
  flex: 1;
  margin-left: 10px;
}

.incidencia-status-box {
  display: flex;
  align-items: center;
}

.incidencia-status {
  padding: 6px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.nueva {
  background-color: rgba($color-new, 0.17);
  color: $color-new;
}

.pendiente {
  background-color: rgba($color-pending, 0.17);
  color: $color-pending;
}

.mantenimiento {
  background-color: rgba($color-maintenance, 0.17);
  color: $color-maintenance;
}

.cerrada {
  background-color: rgba($color-closed, 0.17);
  color: $color-closed;
}

.en-curso {
  background-color: rgba($color-in-progress, 0.17);
  color: $color-in-progress;
}
.card.h-100 {
    height: calc(100vh - 200px) !important;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  .card-bodytotal {
    padding: 1.7rem;
  }
  
  .incidencias-container {
    max-height: 600px;
    overflow-y: auto;
    padding: 0 10px;
  }
  
  /* Add smooth scrollbar for the tickets container */
  .incidencias-container::-webkit-scrollbar {
    width: 8px;
  }
  
  .incidencias-container::-webkit-scrollbar-track {
    background: rgba($color-white, 0.1);
    border-radius: 4px;
  }
  
  .incidencias-container::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
  }
  
  .incidencias-container::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
  }
  
  .form-group {
    margin-bottom: 1rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: $color-dark-gray;
  }
  
  .popup-btn1 {
    background: $color-primary;
    color: $color-white;
    border: none;
    text-align: center;
    font-size: 1rem;
    padding: 0.8rem 1.8rem;
    border-radius: 2rem;
    cursor: pointer;
  }
  
  .popup-btn.primary {
    background-color: rgba($color-white, 0.8);
    color: #000000;
  }
  .popup-btn.danger {
      background-color: $color-danger;
      color: $color-white;
  }
  
  .popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .popup-card {
    width: 700px;
    min-height: 300px;
    background: rgba($color-white, 0.7);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba($color-white, 0.18);
    border-radius: 1.5rem;
    padding: 2rem;
    z-index: 10;
    color: $color-text;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    /* Asegura que el contenido no se salga del borde */
  }
  
  .popup-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    text-align: center;
    color: #222;
  }
  
  .popup-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    text-align: center;
    color: #444;
  }
  
  .popup-content {
      margin-bottom: 2rem;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      /*flex: 1;*/ /* Eliminar o cambiar a flex: none */
      justify-content: flex-start;
      /* Modificado */
      overflow-y: auto;
      /* Añade scroll vertical si el contenido excede el contenedor */
      padding-right: 10px;
      gap: 1rem;
  }
  
  .popup-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 20px;
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
  
  .glassmorphic-btn {
    background: rgba($color-white, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba($color-white, 0.2);
    color: $color-white;
    /* Text color */
    transition: background-color 0.3s ease;
    /* Smooth transition for hover effect */
  
    &:hover {
    background: rgba($color-white, 0.3);
  }
}
  
  
  .popup-btn.primary {
    background-color: rgba($color-white, 0.8);
    color: #000000;
  }
  
  .glassmorphic-btn.cancel-btn {
    color: #000;
    background: rgba($color-white, 0.8);
      &:hover {
        background: rgba($color-white, 0.9);
      }
  }
  
  
  .custom-textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    /* Para que padding no aumente el ancho */
    font-size: 1rem;
    line-height: 1.5;
    transition: border-color 0.3s ease;
    background-color: rgba($color-white, 0.5);
    /* Fondo semi-transparente */
    color: $color-text;
    /* Color del texto */
    &:focus {
        border-color: #007bff;
        outline: none;
        /* Remueve el borde de focus predeterminado del navegador */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        /* Agrega una sombra al enfocar */
    }
      &::placeholder {
        color: #999;
        /* Color del placeholder */
    }
  }
  .action-buttons {
    margin-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}
  .huge-icon {
    max-width: 20px;
}
@media (max-width: 768px) {
    .row {
      --bs-gutter-x: 0.5rem !important;
    }
  
    .col-sm-6 {
      flex: 0 0 auto;
      width: 50% !important;
      max-width: 50% !important;
    }
    .col-12 {
      width: 100% !important;
      max-width: 100% !important;
  }
    .incidencia-list {
      padding: 10px;
      margin-top: 10px;
    }
    .card-body {
      padding: 1rem;
    }
    .card-bodytotal {
      padding: 1rem;
    }
  
    .incidencia-list-header {
      font-size: 0.9rem;
      padding: 0 5px 10px 5px;
      margin-bottom: 10px;
    }
  
    .incidencia-item {
      padding: 10px;
      margin-bottom: 5px;
      gap: 10px;
    }
  
    .incidencia-date {
      min-width: 70px;
      font-size: 0.7rem;
    }
  
    .incidencia-content {
      gap: 10px;
    }
  
    .priority-marker {
      height: 30px;
      margin-right: 3px;
    }
  
    .incidencia-text {
      font-size: 0.8rem;
      margin-left: 5px;
    }
  
    .incidencia-status {
      padding: 4px 8px;
      font-size: 0.7rem;
    }
    .action-buttons {
        flex-direction: row;
        flex-wrap: wrap;
    }
    .popup-card {
        width: 90%;
        min-height: auto;
        max-height: 80vh;
        padding: 1rem;
    }
      .popup-content{
          margin-bottom: 1rem;
      }
  
      .popup-title {
          font-size: 1.5rem;
          margin-bottom: 0.8rem;
      }
  
      .popup-subtitle {
          font-size: 1rem;
          margin-bottom: 1.5rem;
      }
  
      .popup-actions {
          margin-top: 10px;
          gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
      }
    .popup-btn, .popup-btn1 {
          font-size: 0.9rem;
          padding: 0.6rem 1.2rem;
          border-radius: 1.5rem;
          text-align: center;
      }
        .incidencias-container{
            padding: 0 5px;
        }
  
    .priority-legend {
      gap: 10px;
      font-size: 0.8rem;
    }
    .huge-icon {
        max-width: 15px;
    }
  }
  </style>