<template>
  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-pending">
        <div class="card-body">
          <h6>Incidencias pendientes</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="pendiente-h2">{{ incidenciasPendientesCount }}</h2>
            <img src="../assets/images/icons/pendientes.svg">
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
            <img src="../assets/images/icons/curso.svg">
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
            <img src="../assets/images/icons/cerrados.svg">
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-total">
        <div class="card-bodyTotal">
          <h6>Total de incidencias</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="total-h2">{{ totalIncidenciasCount }}</h2>
            <img src="../assets/images/icons/total.svg">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="row g-4">
    <!-- Left Column - Charts -->
    <div class="col-md-4">
      <div class="card glassmorphic-card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-4">
            <div class="incidenciasabiertos">
              <h6 class="text-muted mb-1">Incidencias abiertas</h6>
              <div class="d-flex align-items-baseline">
                <span class="abiertos-h3 mb-0">{{ incidenciasAbiertasHoy }}</span>
                <span class="text-muted ms-2" style="color: rgba(116, 116, 116, 1) !important;">hoy</span>
              </div>
            </div>
          </div>
          <canvas ref="lineChart" height="100"></canvas>
        </div>
      </div>
      <div class="card glassmorphic-card">
        <div class="card-body">
          <h5 class="card-title">Estadísticas</h5>
          <canvas ref="barChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Right Column - Incidencias List -->
    <div class="col-md-8">
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
                <div v-for="incidencia in incidenciasPanelFiltradas" :key="incidencia.id" class="incidencia-item">
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
                              <span class="incidencia-status" :class="incidencia.status.toLowerCase().replace(' ', '-')">
                                {{ incidencia.status }}
                              </span>
                            </div>
                      </div>
                   </div>
             </div>

        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { Chart } from 'chart.js/auto';
import axios from 'axios';

const lineChart = ref(null);
const barChart = ref(null);
const incidencias = ref([]);
const incidenciasPanel = ref([]); // Nuevo ref para las incidencias del panel
const loading = ref(true);
const error = ref(null);
const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
const ALL_INCIDENCIAS_URL = `${API_AUTH_URL}/incidencia/all`;
const ALL_INCIDENCIAS_URL_CERRADAS = `${API_AUTH_URL}/incidencia/all_cerradas`;
const ME_URL = `${API_AUTH_URL}/auth/me`;
const userRole = ref(null);
const userId = localStorage.getItem('id');

const isTecnico = computed(() => userRole.value === 'tecnico');
const isAdmin = computed(() => userRole.value === 'administrador');


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

// Función para formatear la fecha
const formatDate = (dateString) => {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString(undefined, options);
};

// Función para formatear la hora
const formatTime = (dateString) => {
    const date = new Date(dateString);
    const options = { hour: '2-digit', minute: '2-digit' };
    return date.toLocaleTimeString(undefined, options);
};

// Filtra incidencias para no mostrar las cerradas ni en mantenimiento y solo muestra 8 incidencias
const incidenciasFiltradas = computed(() => {
    return incidencias.value
        .slice(0, 8);
});
const incidenciasPanelFiltradas = computed(() => {
    return incidenciasPanel.value
        .filter(incidencia => {
            const estado = obtenerEstado(incidencia.estado);
            return estado !== 'Cerrada' && estado !== 'Mantenimiento';
        })
        .slice(0, 8);
});


// Cálculo del total de incidencias, incluyendo cerradas y en mantenimiento
const totalIncidenciasCount = computed(() => {
    return incidencias.value.length;
});

// Cálculo de incidencias abiertas hoy
const incidenciasAbiertasHoy = computed(() => {
    const today = new Date();
    return incidencias.value.filter(incidencia => {
        const fechaIncidencia = new Date(incidencia.fecha_apertura);
        return fechaIncidencia.getDate() === today.getDate() &&
            fechaIncidencia.getMonth() === today.getMonth() &&
            fechaIncidencia.getFullYear() === today.getFullYear() &&
            obtenerEstado(incidencia.estado) === 'Nueva';
    }).length;
});

// Cálculo de incidencias pendientes
const incidenciasPendientesCount = computed(() => {
    return incidencias.value.filter(incidencia => {
        const estado = obtenerEstado(incidencia.estado);
        return estado === 'Pendiente' || estado === 'Nueva';
    }).length;
});

// Cálculo de incidencias en curso
const incidenciasEnCursoCount = computed(() => {
    return incidencias.value.filter(incidencia => obtenerEstado(incidencia.estado) === 'En curso').length;
});

// Cálculo de incidencias cerradas
const incidenciasCerradasCount = computed(() => {
    return incidencias.value.filter(incidencia => obtenerEstado(incidencia.estado) === 'Cerrada').length;
});


// Función para generar datos para el gráfico de barras
const generateChartData = (incidencias) => {
    const today = new Date();
    const labels = [];
    const resolvedData = [];
    const inProgressData = [];

    for (let i = 6; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const formattedDate = `${day}/${month}`;
        labels.push(formattedDate);

        const resolved = incidencias.filter(incidencia => {
            if (obtenerEstado(incidencia.estado) === 'Cerrada' && incidencia.fecha_cierre) {
                const fechaCierre = new Date(incidencia.fecha_cierre);
                return fechaCierre.getDate() === date.getDate() &&
                    fechaCierre.getMonth() === date.getMonth() &&
                    fechaCierre.getFullYear() === date.getFullYear()
            }
            return false;

        }).length;

        const inProgress = incidencias.filter(incidencia => {
            const fechaIncidencia = new Date(incidencia.fecha_apertura);
            return fechaIncidencia.getDate() === date.getDate() &&
                fechaIncidencia.getMonth() === date.getMonth() &&
                fechaIncidencia.getFullYear() === date.getFullYear() &&
                (obtenerEstado(incidencia.estado) === 'En curso' || obtenerEstado(incidencia.estado) === 'Pendiente' || obtenerEstado(incidencia.estado) === 'Nueva')
        }).length;
        resolvedData.push(resolved);
        inProgressData.push(inProgress)


    }
    return { labels: labels, resolved: resolvedData, opened: inProgressData };
};


// Función para generar datos para el gráfico de líneas
const generateLineChartData = (incidencias) => {
    const today = new Date();
    const hourlyData = Array(24).fill(0); // Initialize an array with 24 elements to store hourly data

    incidencias.forEach(incidencia => {
        const fechaIncidencia = new Date(incidencia.fecha_apertura);
        // Check if the ticket was created today
        if (fechaIncidencia.getDate() === today.getDate() &&
            fechaIncidencia.getMonth() === today.getMonth() &&
            fechaIncidencia.getFullYear() === today.getFullYear()) {
            const hour = fechaIncidencia.getHours();
            hourlyData[hour]++; // If it is today, increment count at this hour
        }

    });

    return hourlyData.slice(0, 24) //return the first 24 items of the array in case there are incidents with wrong data
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
        }
    }
};

const checkUserHasReclamada = () => {
    if (isTecnico.value || isAdmin.value) {
    }
}

onMounted(async () => {
    try {
        await fetchUserData()
        const token = localStorage.getItem('token');
        if (token) {
            try {
                // Petición para las incidencias del panel (all)
                const responsePanel = await axios.post(ALL_INCIDENCIAS_URL, {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                incidenciasPanel.value = responsePanel.data.map(incidencia => ({
                    ...incidencia,
                    priority: obtenerPrioridad(incidencia.prioridad),
                    status: obtenerEstado(incidencia.estado),
                    date: formatDate(incidencia.fecha_apertura),
                    time: formatTime(incidencia.fecha_apertura),
                    descripcion: incidencia.descripcion,
                    titulo: incidencia.titulo,
                    subtitulo: incidencia.subtitulo,
                    fecha_cierre: incidencia.fecha_cierre,
                    id_tecnico: incidencia.id_mantenimiento
                }));
                  // Petición para las incidencias del dashboard (all_cerradas)
                 const responseDashboard = await axios.post(ALL_INCIDENCIAS_URL_CERRADAS,{}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                incidencias.value = responseDashboard.data.map(incidencia => ({
                    ...incidencia,
                    priority: obtenerPrioridad(incidencia.prioridad),
                    status: obtenerEstado(incidencia.estado),
                    date: formatDate(incidencia.fecha_apertura),
                    time: formatTime(incidencia.fecha_apertura),
                    descripcion: incidencia.descripcion,
                    titulo: incidencia.titulo,
                    subtitulo: incidencia.subtitulo,
                    fecha_cierre: incidencia.fecha_cierre,
                    id_tecnico: incidencia.id_mantenimiento
                }));



                const chartData = generateChartData(incidencias.value);
                const lineChartData = generateLineChartData(incidencias.value);


                new Chart(lineChart.value, {
                    type: 'line',
                    data: {
                        labels: ['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
                        datasets: [{
                            label: 'Incidencias',
                            data: lineChartData,
                            borderColor: '#600484',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(116, 116, 116, 1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: 'rgba(116, 116, 116, 1)',
                                    padding: 10
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: 'rgba(116, 116, 116, 1)',
                                    padding: 10
                                }
                            }
                        }
                    }
                });


                new Chart(barChart.value, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Promedio incidencias resueltas',
                                data: chartData.resolved,
                                backgroundColor: '#600484',
                                borderRadius: 10,
                                stack: 'combined',
                            },
                            {
                                label: 'Promedio incidencias abiertas',
                                data: chartData.opened,
                                backgroundColor: '#a470c2',
                                borderRadius: 10,
                                stack: 'combined',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 40,
                                ticks: {
                                    stepSize: 10,
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                            },
                            roundedBars: true
                        },
                    }
                });
            } catch (err) {
                error.value = err;
            } finally {
                loading.value = false;
            }
            checkUserHasReclamada();
        }
    } catch (err) {
        error.value = err;
        loading.value = false;
    }
});

watch(incidencias, () => {
    checkUserHasReclamada();
});

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
  justify-content: flex-end;
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
.total-h2{
  color: #ffffff;
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
    display: flex;
    flex-direction: column;
}

.card-bodyTotal {
    padding: 1.8rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
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



.text-sm {
  font-size: 0.875rem;
  color: #000000;
}

.btn-ver-todos {
  background: transparent;
  border: none;
  color: #000000;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  padding: 0;
}

.incidencias-list {
  margin-top: 1rem;
}

.incidencia-row {
    display: flex;
    align-items: flex-start;
    margin-bottom: 0.5rem;
    min-height: 60px;
    width: 100%;
}

.incidencia-priority-bar {
    width: 5px;
    height: 40px;
    border-radius: 2px;
    flex-shrink: 0;
    margin-right: 5px;
}

.incidencia-priority-bar.alta {
  background-color: #FF5252;
}

.incidencia-priority-bar.media {
  background-color: #FFCA28;
}

.incidencia-priority-bar.baja {
  background-color: #4CAF50;
}

.incidencia-content {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    flex: 1;
    width: 100%;
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

.incidencia-description {
    display: flex;
    flex-direction: column;
    font-size: 0.9rem;
    line-height: 1.3;
    flex: 1;
    margin-left: 10px;
}

.incidencia-title {
    color: #000000;
    margin-bottom: 0.25rem;
}


.incidencia-subtitle {
    color: #747474;
    font-size: 0.875rem;
}

.incidencia-status-box {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.incidencia-status {
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}
</style>