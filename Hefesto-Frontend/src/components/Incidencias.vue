<template>
  <div class="row g-4 mb-4">
    <!-- Header con los paneles -->
    <div class="col-sm-6 col-md-3">
      <div
        class="card glassmorphic-card colored-shadow-pending create-incidencia-card"
        @click="openCreateIncidenciaPopup"
      >
        <div
          class="card-bodytotal card-body-same-height d-flex justify-content-between align-items-center"
        >
          <h6 class="mb-0">Crear incidencia</h6>
          <img src="../assets/images/icons/crear.svg" />
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-pending">
        <div class="card-body">
          <h6>Incidencias pendientes</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="pendiente-h2">{{ incidenciasPendientesCount }}</h2>
            <img src="../assets/images/icons/pendientes.svg" />
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-in-progress">
        <div class="card-body">
          <h6>Incidencias en curso</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="curso-h2">{{ incidenciasEnCursoCount }}</h2>
            <img src="../assets/images/icons/curso.svg" />
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-closed">
        <div class="card-body">
          <h6>Incidencias cerradas</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="cerrado-h2">{{ incidenciasCerradasCount }}</h2>
            <img src="../assets/images/icons/cerrados.svg" />
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="incidencia-list glassmorphic-card">
        <div class="incidencia-list-header">
          <div>Incidencias</div>
          

          <div class="priority-legend">
            <div class="boton-filtro" @click="openFiltroPopup">
              <img src="../assets/images/icons/filtro.svg" alt="Filtro" class="imagen-filtro">
              Filtros
            </div>
          </div>

        <div class="priority-legend">
          <span
            @click="seleccionarPrioridad('alta')"
            :class="{ 'selected': prioridadSeleccionada === 'alta' }"
            class="priority-filter"
          >
            <span class="priority-dot alta"></span> Alta
          </span>
          <span
            @click="seleccionarPrioridad('media')"
            :class="{ 'selected': prioridadSeleccionada === 'media' }"
            class="priority-filter"
          >
            <span class="priority-dot media"></span> Media
          </span>
          <span
            @click="seleccionarPrioridad('baja')"
            :class="{ 'selected': prioridadSeleccionada === 'baja' }"
            class="priority-filter"
          >
            <span class="priority-dot baja"></span> Baja
          </span>
          <span
            @click="seleccionarPrioridad(null)"
            :class="{ 'selected': prioridadSeleccionada === null }"
            class="priority-filter"
          >
            Todos
          </span>
        </div>
      </div>
      <div v-if="loading">Cargando incidencias...</div>
      <div v-else-if="error">Error al cargar las incidencias.</div>
      <div v-else class="incidencias-container">
        <div
          v-for="incidencia in filteredIncidencia"
          :key="incidencia.date + incidencia.time"
          class="incidencia-item"
          @click="handleIncidenciaClick(incidencia)"
        >
          <!-- Restructured layout to match the image -->
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
    <GlassmorphicPopup
      :visible="showCreateIncidenciaPopup"
      title="Crear Nueva Incidencia"
      closeButtonText="Cancelar"
      actionButtonText="Crear Incidencia"
      @close="closeCreateIncidenciaPopup"
      @action="handleCreateIncidencia"
    >
      <template #popup-content>
        <div class="form-group mb-3">
          <label for="titulo">Título:</label>
          <CustomInput placeholder="Ingrese el título" v-model="newIncidencia.titulo" />
        </div>
        <div class="form-group mb-3">
          <label for="subtitulo">Subtítulo:</label>
          <CustomInput
            placeholder="Ingrese el subtítulo"
            v-model="newIncidencia.subtitulo"
          />
        </div>
        <div class="form-group mb-3">
          <label for="tipoIncidencia">Tipo de Incidencia:</label>
          <CustomSelect
            :options="tiposIncidencia"
            v-model="newIncidencia.id_tipo_incidencia"
          />
        </div>
        <div class="form-group mb-3">
          <label for="maquina">Máquina:</label>
          <CustomSelect :options="maquinas" v-model="newIncidencia.id_maquina" />
        </div>
      </template>
    </GlassmorphicPopup>
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
            <div class="action-buttons mt-auto"  v-if="!isOperario">
             <button
               v-if="(isTecnico || isAdmin) && !isUserActiveInIncidencia && selectedIncidencia.status !== 'Cerrada'"
                class="popup-btn primary"
                @click="handleReclamarIncidencia"
                :disabled="reclamandoIncidencia"
              >
                {{ reclamandoIncidencia ? 'Reclamando...' : 'Reclamar Incidencia' }}
              </button>
              <div v-if="isUserActiveInIncidencia && selectedIncidencia.status === 'En curso'" class="d-flex justify-content-center gap-2">
                <button class="popup-btn1 cancel-btn" @click="openMotivoSalidaPopup">Salir de
                  Incidencia
                </button>
                <button  v-if="isSoloTecnico" class="popup-btn primary" @click="openMotivoCierrePopup">Cerrar Incidencia
                </button>
              </div>
            </div>
        </div>
        <div v-else>
          <p>No hay detalles de incidencia para mostrar</p>
        </div>
      </template>
    </GlassmorphicPopup>
     <GlassmorphicPopup  v-if="!isOperario"
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
          :visible="showErrorPopup"
          title="Error"
          closeButtonText="Cerrar"
          @close="closeErrorPopup"
      >
          <template #popup-content>
              <p>{{ errorMessage }}</p>
          </template>
      </GlassmorphicPopup>
    <GlassmorphicPopup  v-if="!isOperario"
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
    <GlassmorphicPopup
      :visible="showFiltroPopup"
      title="Filtros incidencias"
      closeButtonText="Cancelar"
      actionButtonText="Aplicar"
      @close="cancelFilters"
      @action="closeFiltroPopup"
      >
      
      <template #popup-content>
        <div class="row">
          <div class="col">

            <div class="search-bar">
              <CustomSelect
                    :options="campusOptions"
                    :modelValue="selectedCampus"
                    @update:modelValue="handleFilterCampusSelect"
                    placeholder="Filtrar por campus"
                    class="glassmorphic-select"
                  />
                  <CustomSelect
                    :options="filteredSeccionOptions"
                    :modelValue="selectedSeccion"
                    @update:modelValue="handleFilterSeccionSelect"
                    placeholder="Filtrar por seccion"
                    class="glassmorphic-select"
                  />
            </div>
            <p>Filtros</p>
          </div>
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

const loading = ref(true);
const error = ref(null);
const incidencias = ref([]);
const incidenciasPanel = ref([]); 
const tiposIncidencia = ref([]);
const maquinas = ref([]);
const tecnicoIncidencias = ref([]);
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const ALL_INCIDENCIAS_URL = `${API_AUTH_URL}/incidencia/all`;
const ALL_INCIDENCIAS_URL_CERRADAS = `${API_AUTH_URL}/incidencia/all_cerradas`;
const ALL_TIPO_INCIDENCIAS_URL = `${API_AUTH_URL}/tipo_incidencia/all`;
const ALL_MAQUINAS_URL = `${API_AUTH_URL}/maquina/all`;
const ALL_TECNICO_INCIDENCIA_URL = `${API_AUTH_URL}/tecnico_incidencia/all`;
const ME_URL = `${API_AUTH_URL}/auth/me`;
const CAMPUS_ALL_URL = `${API_AUTH_URL}/campus/all`;
const SECCION_ALL_URL = `${API_AUTH_URL}/seccion/all`;
const userPicture = ref(null);
const userId = ref(null);
const userRole = ref(null);
const prioridadSeleccionada = ref(null); 
const seleccionarPrioridad = (prioridad) => {
  prioridadSeleccionada.value = prioridad;
};
console.log('userId from localStorage:', userId);
const isTecnico = computed(() => userRole.value === 'tecnico');
const isAdmin = computed(() => userRole.value === 'administrador');
const isOperario = computed(() => userRole.value === 'operario');
const userImagePath = computed(() => {
    return userPicture ? `${IMAGE_URL}${userPicture}` : null;
});
const showMotivoSalidaPopup = ref(false);
const motivoSalida = ref('');
const showCreateIncidenciaPopup = ref(false);
const showFiltroPopup = ref(false);
const guardandoComentario = ref(false);
const newIncidencia = ref({
    titulo: '',
    subtitulo: '',
    id_tipo_incidencia: null,
    id_maquina: null,
});
const reclamandoIncidencia = ref(false);
const showMotivoCierrePopup = ref(false);
const motivoCierre = ref('');
const debouncedUpdateDescription = ref(null);
const hasReclamada = ref(false);
const showErrorPopup = ref(false);
const errorMessage = ref('');
const isSoloTecnico = computed(()=>{
    if(selectedIncidencia.value && (isTecnico.value || isAdmin.value)){
    const count = tecnicoIncidencias.value.filter(incidencia => incidencia.id_incidencia === selectedIncidencia.value.id && incidencia.estado_tecnico === 'activo').length;
     console.log('isSoloTecnico:', count === 1, 'count:', count);
    return count === 1;
    }
    return false
})

const obtenerPrioridad = (prioridad) => {
    if (prioridad === "alta" || prioridad ==="media" || prioridad === "baja") {
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
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString(undefined, options);
};

const formatTime = (dateString) => {
    const date = new Date(dateString);
    const options = { hour: '2-digit', minute: '2-digit' };
    return date.toLocaleTimeString(undefined, options);
};


const fetchData = async (url) => {
    const token = localStorage.getItem('token');
    if (!token) {
        throw new Error('No token found');
    }
    try {
        const response = await axios.post(
            url,
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
        throw new Error('Error al obtener los datos de las incidencias');
    }
};

const fetchTecnicoIncidencias = async () => {
    try {

      if(!isOperario.value){
        const token = localStorage.getItem('token');
        if (!token) {
          throw new Error('No token found');
        }
        const response = await axios.get(ALL_TECNICO_INCIDENCIA_URL, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
          tecnicoIncidencias.value = response.data.data;
          console.log('tecnicoIncidencias:', tecnicoIncidencias.value);
      }
      
    } catch (error) {
      console.error('Error al obtener los técnicos de las incidencias:', error);
      throw new Error('Error al obtener los técnicos de las incidencias');
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
        throw new Error('Error al obtener las maquinas');
    }
};

const incidenciasPendientesCount = computed(() => {
    return incidencias.value.filter(
        (incidencia) => {
            const estado = obtenerEstado(incidencia.estado);
            return estado === 'Pendiente' || estado === 'Nueva';
        }
    ).length;
});

const incidenciasEnCursoCount = computed(() => {
    return incidencias.value.filter(
        (incidencia) => obtenerEstado(incidencia.estado) === 'En curso'
    ).length;
});

const incidenciasCerradasCount = computed(() => {
    return incidencias.value.filter(
        (incidencia) => obtenerEstado(incidencia.estado) === 'Cerrada'
    ).length;
});

const loadIncidencias = async (url) => {
    try {
        loading.value = true;
        const incidenciasData = await fetchData(url);

        return incidenciasData.map((incidencia) => ({
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
        throw new Error(err);
    } finally {
        loading.value = false;
    }
}
const isUserActiveInIncidencia = computed(() => {
    if (!selectedIncidencia.value || !userId.value) return false;
    return tecnicoIncidencias.value.some(
        (item) =>
        item.id_incidencia === selectedIncidencia.value.id &&
        item.id_tecnico === Number(userId.value) &&
        item.estado_tecnico === 'activo'
    );
});
const loadIncidenciasDashboard = async () => {
    try {
        incidencias.value = await loadIncidencias(ALL_INCIDENCIAS_URL_CERRADAS)
    } catch (err){
        error.value = err;
    }
};
const loadIncidenciasPanel = async () => {
    try {
        incidenciasPanel.value = await loadIncidencias(ALL_INCIDENCIAS_URL)
    }catch(err){
        error.value = err;
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
            userPicture.value = response.data.picture;
            userId.value = response.data.id
             localStorage.setItem('id', response.data.id);


        } catch (error) {
            console.error('Error fetching user data:', error);
        }
    }
};
const checkUserHasReclamada = () => {
    if(isTecnico.value || isAdmin.value){
         hasReclamada.value = tecnicoIncidencias.value.some(incidencia => incidencia.id_tecnico === Number(userId.value) && incidencia.estado_tecnico === 'activo')
    }
}

const searchQuery = ref('');
const selectedSeccion = ref(null);
const seccionOptions = ref([]);
const selectedCampus = ref(null);
const campusOptions = ref([]);

const filteredIncidencia = computed(() => {
  if (!incidenciasPanel.value) {
    return [];
  }
  let filtered = [...incidenciasPanel.value];
  if (searchQuery.value) {
    const searchTerm = searchQuery.value.toLowerCase();
    filtered = filtered.filter(incidencia => {
      const incidenciaName = `${incidencia.titulo}`.toLowerCase();
      return incidenciaName.includes(searchTerm);
    });
  }

    if (selectedSeccion.value) {
        filtered = filtered.filter(incidencia => {
        const maquina = maquinas.value.find(maquina => maquina.id === incidencia.id_maquina);
        return maquina?.id_seccion === selectedSeccion.value;
    });
    }

    if (selectedCampus.value) {
        filtered = filtered.filter(incidencia => {
             const maquina = maquinas.value.find(maquina => maquina.id === incidencia.id_maquina);
            const seccion = seccionOptions.value.find(seccion => seccion.id === maquina?.id_seccion);
          return seccion?.id_campus === selectedCampus.value;
        });
      }

  return filtered;
});

const filteredSeccionOptions = computed(() => {
  if (selectedCampus.value) {
    return seccionOptions.value.filter(seccion => seccion.id_campus === selectedCampus.value);
  }
  return seccionOptions.value;
});

const handleFilterSeccionSelect = (seccionId) =>{
  selectedSeccion.value = seccionId;
}

const handleFilterCampusSelect = (campusId) =>{
  selectedCampus.value = campusId;
  selectedSeccion.value = null;
}

const searchQueryTemp = ref('');
const selectedSeccionTemp = ref(null);
const selectedCampusTemp = ref(null);

const cancelFilters = () => {
    searchQueryTemp.value = "";
    selectedSeccionTemp.value = null;
    selectedCampusTemp.value = null;
    searchQuery.value = "";
    selectedSeccion.value = null;
    selectedCampus.value = null;
    closeFiltroPopup();
};

onMounted(async () => {
    try {
      const token = localStorage.getItem('token');
      const headers = {
        Authorization: `Bearer ${token}`,
      };
        await fetchUserData()
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
            id_seccion: maquina.id_seccion
        }));

        const response = await axios.get(SECCION_ALL_URL, { headers });
          seccionOptions.value = response.data.data.map((seccion) => ({
            id: seccion.id,
            label: seccion.nombre_seccion,
            id_campus: seccion.id_campus,
        }));

        const campusResponse = await axios.get(CAMPUS_ALL_URL, { headers });
        campusOptions.value = campusResponse.data.data.map((campus) => ({
          id: campus.id,
          label: campus.nombre_campus,
        }));

        await Promise.all([
            loadIncidenciasDashboard(),
            loadIncidenciasPanel(),
            fetchTecnicoIncidencias()
        ]);
        checkUserHasReclamada();
    } catch (err) {
        error.value = err;
        loading.value = false;
    }
});
watch(incidencias,()=>{
    checkUserHasReclamada();
});

const openCreateIncidenciaPopup = () => {
    showCreateIncidenciaPopup.value = true;
};

const openFiltroPopup = () => {
  showFiltroPopup.value = true;
}

const closeFiltroPopup = () => {
  showFiltroPopup.value = false;
}

const closeCreateIncidenciaPopup = () => {
    showCreateIncidenciaPopup.value = false;
    newIncidencia.value = {
        titulo: '',
        subtitulo: '',
        id_tipo_incidencia: null,
        id_maquina: null,
    };
};
const validateIncidenciaData = () => {
    if (!newIncidencia.value.titulo || !newIncidencia.value.subtitulo || !newIncidencia.value.id_tipo_incidencia || !newIncidencia.value.id_maquina) {
        alert('Por favor, complete el título, el subtítulo, el tipo de incidencia y la máquina.');
        return false;
    }
    return true;
};

const handleCreateIncidencia = async () => {
    if (!validateIncidenciaData()) {
        return;
    }
    const token = localStorage.getItem('token');
    if (!token) {
        throw new Error('No token found');
    }
    try {
        const apiUrl = `${API_AUTH_URL}/incidencia/store`;
        const fecha_apertura = new Date();
        const formattedDate = fecha_apertura.toISOString().slice(0, 19).replace('T', ' ');

        const requestData = {
            titulo: newIncidencia.value.titulo,
            subtitulo: newIncidencia.value.subtitulo,
            estado: 0,
            id_maquina: Number(newIncidencia.value.id_maquina),
            tipo_incidencia: Number(newIncidencia.value.id_tipo_incidencia),
        };

        const response = await axios.post(apiUrl, requestData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.status === 201) {
            closeCreateIncidenciaPopup();
             await Promise.all([
            loadIncidenciasDashboard(),
            loadIncidenciasPanel()
        ]);
        }
    } catch (error) {
        console.error('Error al crear la incidencia:', error);
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

const closeIncidenciaDetailsPopup = () => {
    showIncidenciaDetailsPopup.value = false;
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
        const apiUrl = `${API_AUTH_URL}/tecnico_incidencia/reclamar_incidencia_multiple`;
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
          await Promise.all([
        loadIncidenciasDashboard(),
        loadIncidenciasPanel(),
        fetchTecnicoIncidencias()
    ]);
     if(selectedIncidencia.value.status !== 'En curso'){
            selectedIncidencia.value.status = 'En curso';
        }
        console.log('selectedIncidencia.status', selectedIncidencia.value.status)
    }
        else {
             if (response.status === 400) {
                errorMessage.value = 'No puedes reclamar más de una incidencia a la vez.';
                showErrorPopup.value = true;
            } else {
                console.error('Error al reclamar la incidencia, respuesta no exitosa', response);
                alert("Error al reclamar la incidencia: " + (response.data.message || "Compruebe la consola para más información"));
            }
        }
    }catch(error){
        if (error.message.includes('400')) {
                 errorMessage.value = 'No puedes reclamar más de una incidencia a la vez.';
                showErrorPopup.value = true;
            } else{
                 console.error('Error al reclamar la incidencia:', error);
                alert("Error al reclamar la incidencia: " + (error.message || "Compruebe la consola para más información"));
         }
    }finally{
        reclamandoIncidencia.value = false;
    }
}
const closeErrorPopup = () =>{
    showErrorPopup.value         = false;
    errorMessage.value = '';
}
const openMotivoSalidaPopup = () => {
    showMotivoSalidaPopup.value = true;
};

const closeMotivoSalidaPopup = () => {
    showMotivoSalidaPopup.value = false;
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
           await Promise.all([
            loadIncidenciasDashboard(),
            loadIncidenciasPanel()
        ]);
        }
    }catch (error){
        console.error('Error al salir de la incidencia:',error);
    }
}
const openMotivoCierrePopup = () => {
    showMotivoCierrePopup.value = true;
};

const closeMotivoCierrePopup = () => {
    showMotivoCierrePopup.value = false;
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
            await  Promise.all([
            loadIncidenciasDashboard(),
            loadIncidenciasPanel()
        ]);
             selectedIncidencia.value.status = 'Cerrada';
            closeMotivoCierrePopup();
            closeIncidenciaDetailsPopup();
        }
    }catch (error){
        console.error('Error al cerrar la incidencia', error);
    }
}
</script>
<style scoped>
.incidencia-panel {
  width: 100%;
  padding: 20px;
  background-color: #f8f8f8;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.incidencia-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.create-incidencia-btn {
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 12px 20px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
}

.create-incidencia-btn:hover {
  background-color: #f0f0f0;
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
  background-color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 15px;
  border-radius: 5px;
  min-width: 140px;
  gap: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.incidencia-stat-box.active {
  background-color: #e3fae8;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
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
  background-color: rgba(255, 255, 255, 0.7) !important;
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
  color: #666;
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
  background-color: #FF5252;
}

.media {
  background-color: #FFCA28;
}

.baja {
  background-color: #4CAF50;
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
}

.incidencia-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.incidencia-date {
  display: flex;
  flex-direction: column;
  font-size: 0.8rem;
  color: #666;
  min-width: 100px;
  flex-shrink: 0;
}

.incidencia-date span:first-child {
  font-weight: 500;
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
  background-color: rgba(184, 155, 0, 0.17);
  color: #B89B00;
}

.pendiente {
  background-color: rgba(184, 155, 0, 0.17);
  color: #B89B00;
}

.mantenimiento {
  background-color: rgba(0, 0, 0, 0.17);
  color: #000000;
}

.cerrada {
  background-color: rgba(0, 0, 0, 0.17);
  color: #000000;
}

.en-curso {
  background-color: rgba(96, 4, 132, 0.17);
  color: #600484;
}

.create-incidencia-card {
  cursor: pointer;
  transition: all 0.3s;
}

.create-incidencia-card:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.7);
}

.card-body-same-height {
  min-height: 100px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.glassmorphic-card {
  background: rgba(255, 255, 255, 0.7) !important;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.05) !important;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
}

.pendiente-h2 {
  color: #B89B00;
  font-size: 70px;
}

.curso-h2 {
  color: #600484;
  font-size: 70px;
}

.cerrado-h2 {
  color: #000000;
  font-size: 70px;
}

.text-muted {
  color: rgba(54, 54, 54, 0.6) !important;
}

.h3 {
  color: rgba(255, 255, 255, 0.9);
}

table {
  background-color: transparent !important;
}

.table-responsive {
  max-height: calc(100vh - 300px);
  overflow-y: auto;
  background-color: transparent !important;
}

.table > :not(caption) > * > * {
  background-color: transparent !important;
}

canvas {
  max-height: 300px;
}

.status-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
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
  background: rgba(255, 255, 255, 0.1);
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
  color: #666;
}

.popup-btn1 {
  background: rgba(0, 123, 255, 0.9);
  color: white;
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
.popup-btn.danger {
    background-color: rgba(255, 0, 0, 0.8);
    color: white;
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
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #fff;
  /* Text color */
  transition: background-color 0.3s ease;
  /* Smooth transition for hover effect */
}

.glassmorphic-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

.popup-btn.primary {
  background-color: rgba(255, 255, 255, 0.8);
  color: #000000;
}

.glassmorphic-btn.cancel-btn {
  color: #000;
  background: rgba(255, 255, 255, 0.8);
}

.glassmorphic-btn.cancel-btn:hover {
  background: rgba(255, 255, 255, 0.9);
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
  background-color: rgba(255, 255, 255, 0.5);
  /* Fondo semi-transparente */
  color: #333;
  /* Color del texto */
}

.custom-textarea:focus {
  border-color: #007bff;
  outline: none;
  /* Remueve el borde de focus predeterminado del navegador */
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  /* Agrega una sombra al enfocar */
}

.custom-textarea::placeholder {
  color: #999;
  /* Color del placeholder */
}
.action-buttons {
    margin-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

:root {
    --color-primario: #007bff;
}

.priority-filter {
  cursor: pointer; /* Cambia el cursor a "mano" */
  position: relative; /* Necesario para posicionar la barra */
  padding-bottom: 3px; /* Espacio para la barra */
}

.priority-filter:hover {
  opacity: 0.8; /* Reduce la opacidad al pasar el ratón */
}

.priority-filter.selected {
  /* Añade el subrayado */
  text-decoration-color: var(--color-primario); /* Color del subrayado */
  text-decoration-thickness: 4px; /* Grosor del subrayado */
}


.boton-filtro{
  cursor: pointer;
  position: relative;
  padding: 5px;
  display: flex;
  align-items: center;
  border-radius: 12px;
  border: 0.5px solid black;
}

.boton-filtro:hover{
  opacity: 0.8;
}






/* Ajusta el espaciado entre los elementos de la leyenda */
.priority-legend span {
  margin-right: 10px; /* Ajusta el valor según sea necesario */
}


.imagen-filtro{
  width: 20px;
}


.glassmorphic-select{
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 10px;
  padding: 10px;
  color: #333;
  width: 300px;
}

.search-bar {
  margin-bottom: 20px;
  display: flex;
  gap: 10px;
  align-items: center;
  justify-content: center;
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

  .incidencia-list {
    padding: 10px;
    margin-top: 10px;
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

  .card-body {
    padding: 1rem;
  }
  .card-bodytotal {
    padding: 1rem;
  }
  .pendiente-h2,
  .curso-h2,
  .cerrado-h2 {
    font-size: 45px;
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
   .boton-filtro {
    padding: 3px;
  }
    .imagen-filtro{
        width: 15px;
    }

  .col-12 {
    width: 100% !important;
    max-width: 100% !important;
  }

}

</style>