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
            <span><span class="priority-dot alta"></span> Alta</span>
            <span><span class="priority-dot media"></span> Media</span>
            <span><span class="priority-dot baja"></span> Baja</span>
          </div>
        </div>
        <div v-if="loading">Cargando incidencias...</div>
        <div v-else-if="error">Error al cargar las incidencias.</div>
        <div v-else class="incidencias-container">
          <div
            v-for="incidencia in incidencias"
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
              class="form-control"
              rows="3"
              placeholder="Agrega un comentario"
            ></textarea>
             <button 
              class="btn btn-primary mt-2"
              @click="handleUpdateDescription"
              :disabled="guardandoComentario"
              >
              {{ guardandoComentario ? 'Guardando...' : 'Guardar Comentario' }}
           </button>
            </div>
            <div v-else-if="selectedIncidencia.descripcion">
             <p><strong>Comentario:</strong> {{ selectedIncidencia.descripcion }}</p>
           </div>
             <div v-else>
               <p>No hay comentarios para mostrar</p>
             </div>
           <div class="action-buttons">
              <button 
                      class="btn btn-primary" 
                      @click="handleReclamarIncidencia"
                      :disabled="reclamandoIncidencia">
                {{ reclamandoIncidencia ? 'Reclamando...' : 'Reclamar Incidencia' }}
              </button>
                <div v-if="isTecnico && selectedIncidencia.status === 'En curso'">
                  <button class="btn btn-danger me-2" @click="openMotivoSalidaPopup">Salir de Incidencia</button>
                  <button class="btn btn-success" @click="handleCerrarIncidencia">Cerrar Incidencia</button>
                </div>
           </div>
        </div>
        <div v-else>
          <p>No hay detalles de incidencia para mostrar</p>
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
            <textarea id="motivoSalida" v-model="motivoSalida" class="form-control" rows="3" placeholder="Indique el motivo de su salida"></textarea>
        </div>
    </template>
    </GlassmorphicPopup>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import GlassmorphicPopup from './GlassmorphicPopup.vue';
import CustomSelect from './CustomSelect.vue';
import CustomInput from './CustomInput.vue';

const loading = ref(true);
const error = ref(null);
const incidencias = ref([]);
const tiposIncidencia = ref([]);
const maquinas = ref([]);
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const ALL_INCIDENCIAS_URL = `${API_AUTH_URL}/incidencia/all`;
const ALL_TIPO_INCIDENCIAS_URL = `${API_AUTH_URL}/tipo_incidencia/all`;
const ALL_MAQUINAS_URL = `${API_AUTH_URL}/maquina/all`;
const userRole = localStorage.getItem('rol');
const userPicture = localStorage.getItem('picture');
const userId = localStorage.getItem('id');
const isTecnico = computed(() => userRole === 'tecnico');
const isAdmin = computed(() => userRole === 'administrador');
const isOperario = computed(() => userRole === 'operario');
const userImagePath = computed(() => {
  return userPicture ? `${IMAGE_URL}${userPicture}` : null;
});
const showMotivoSalidaPopup = ref(false);
const motivoSalida = ref('');
const showCreateIncidenciaPopup = ref(false);
const guardandoComentario = ref(false);
const newIncidencia = ref({
  titulo: '',
  subtitulo: '',
  id_tipo_incidencia: null,
  id_maquina: null,
});
const reclamandoIncidencia = ref(false);

const obtenerPrioridad = (id_tipo_incidencia) => {
  switch (id_tipo_incidencia) {
    case 1:
      return 'alta';
    case 2:
      return 'media';
    case 3:
      return 'baja';
    default:
      return 'baja';
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
    (incidencia) => obtenerEstado(incidencia.estado) === 'Nueva'
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

const loadIncidencias = async () => {
  try {
    loading.value = true;
    const incidenciasData = await fetchData();

    incidencias.value = incidenciasData.map((incidencia) => ({
      ...incidencia,
      id: incidencia.id,
      priority: obtenerPrioridad(incidencia.id_tipo_incidencia),
      status: obtenerEstado(incidencia.estado),
      date: formatDate(incidencia.fecha_apertura),
      time: formatTime(incidencia.fecha_apertura),
      descripcion: incidencia.descripcion
    }));
  } catch (err) {
    error.value = err;
  } finally {
    loading.value = false;
  }
}


onMounted(async () => {
  try {
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

      await loadIncidencias();
  } catch (err) {
    error.value = err;
      loading.value = false;
  }
});

const openCreateIncidenciaPopup = () => {
  showCreateIncidenciaPopup.value = true;
};

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
          id_creador: 1,
          id_mantenimiento: 1,
      };

      const response = await axios.post(apiUrl, requestData, {
          headers: {
              Authorization: `Bearer ${token}`,
          },
      });

      if (response.status === 201) {
          closeCreateIncidenciaPopup();
          await loadIncidencias();
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
      closeIncidenciaDetailsPopup();
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
           console.error('Error al reclamar la incidencia, respuesta no exitosa', response);
           alert("Error al reclamar la incidencia: " + (response.data.message || "Compruebe la consola para más información"));
        }
    }catch(error){
       console.error('Error al reclamar la incidencia:', error);
          alert("Error al reclamar la incidencia: " + (error.message || "Compruebe la consola para más información"));
    }finally{
        reclamandoIncidencia.value = false;
    }
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
            motivo: motivoSalida.value
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
    }
}

const handleCerrarIncidencia = async () => {
    if(!selectedIncidencia.value) return;
    const token = localStorage.getItem('token');
    if(!token){
        throw new Error('No token found');
    }
    try{
        const apiUrl = `${API_AUTH_URL}/tecnico_incidencia/cerrar_incidencia`;
        const requestData = {
            id_incidencia: selectedIncidencia.value.id
        };

        const response = await axios.put(apiUrl,requestData,{
            headers:{
                Authorization: `Bearer ${token}`
            }
        });
        if(response.status === 200){
            await loadIncidencias();
             selectedIncidencia.value.status = 'Cerrada';
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
</style>