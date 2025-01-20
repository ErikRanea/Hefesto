<template>
  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-pending">
        <div class="card-body">
          <h6>Incidencias pendientes</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2 class="pendiente-h2">10</h2>
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
            <h2 class="curso-h2">10</h2>
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
            <h2 class="cerrado-h2">10</h2>
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
            <h2 class="total-h2">10</h2>
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
                <span class="abiertos-h3 mb-0">20</span>
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
      <div class="card glassmorphic-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Últimas incidencias</h5>
            <div class="d-flex align-items-center gap-4">
              <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                  <span class="status-dot alta"></span>
                  <span class="ms-1 text-sm">Alta</span>
                </div>
                <div class="d-flex align-items-center">
                  <span class="status-dot media"></span>
                  <span class="ms-1 text-sm">Media</span>
                </div>
                <div class="d-flex align-items-center">
                  <span class="status-dot baja"></span>
                  <span class="ms-1 text-sm">Baja</span>
                </div>
              </div>
              <button class="btn-ver-todos">
                Ver todos <i class="bi bi-chevron-right ms-1"></i>
              </button>
            </div>
          </div>

          <div class="incidencias-list">
            <div v-for="(incidencia, index) in incidencias" :key="index"
                 class="incidencia-row">
              <div class="incidencia-priority-bar" :class="incidencia.priority"></div>
              <div class="incidencia-content">
                <div class="incidencia-date">
                  <div>{{ incidencia.date }}</div>
                  <div>{{ incidencia.time }}</div>
                </div>
                <div class="incidencia-description">
                  <div class="incidencia-title">{{ incidencia.title }}</div>
                  <div class="incidencia-subtitle">{{ incidencia.subtitle }}</div>
                </div>
                <div class="incidencia-status">
                  <span class="status-badge" :class="incidencia.status.toLowerCase()">
                    {{ incidencia.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Chart } from 'chart.js/auto'

const lineChart = ref(null)
const barChart = ref(null)

const incidencias = [
  {
    priority: 'alta',
    date: 'Enero 16, 2025',
    time: '8:45',
    title: 'La fresadora tiene ruidos raros pero funciona',
    subtitle: 'Ayer por la mañana no tenía ningún ruido extra...',
    status: 'Pendiente'
  },
  {
    priority: 'media',
    date: 'Enero 16, 2025',
    time: '8:45',
    title: 'La fresadora tiene ruidos raros pero funciona',
    subtitle: 'Ayer por la mañana no tenía ningún ruido extra...',
    status: 'Cerrada'
  },
  {
    priority: 'baja',
    date: 'Enero 16, 2025',
    time: '8:45',
    title: 'La fresadora tiene ruidos raros pero funciona',
    subtitle: 'Ayer por la mañana no tenía ningún ruido extra...',
    status: 'Abierta'
  },
  {
    priority: 'alta',
    date: 'Enero 16, 2025',
    time: '8:45',
    title: 'La fresadora tiene ruidos raros pero funciona',
    subtitle: 'Ayer por la mañana no tenía ningún ruido extra...',
    status: 'Nueva'
  }
]

onMounted(() => {
  // Line Chart
  new Chart(lineChart.value, {
    type: 'line',
    data: {
      labels: ['8:00', '12:00', '15:00', '20:00', '00:00'],
      datasets: [{
        label: 'Incidencias',
        data: [5, 15, 8, 12, 7],
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
  })

  // Bar Chart
  new Chart(barChart.value, {
    type: 'bar',
    data: {
      labels: ['16/01', '17/01', '18/01', '19/01', '20/01', '21/01', '22/01'],
      datasets: [
        {
          label: 'Promedio incidencias resueltas',
          data: [15, 12, 16, 16, 20, 13, 16],
          backgroundColor: '#600484',
           borderRadius: 10, 
           stack: 'combined',
        },
        {
          label: 'Promedio incidencias abiertas',
          data: [19, 10, 8, 16, 19, 9, 11],
          backgroundColor: '#a470c2',
           borderRadius: 10, 
          stack: 'combined',
        }
      ]
    },
    options: {
      responsive: true,
      scales:{
        y:{
            beginAtZero: true,
           max:40,
           ticks:{
                stepSize:10,
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
})
</script>

<style scoped>
.glassmorphic-card {
  background: rgba(255, 255, 255, 0.7) !important;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.05) !important;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
}

.text-muted {
  color: rgba(116, 116, 116, 1) !important;
}

.h3 {
  color: rgba(255, 255, 255, 0.466);
}

table {
  color: rgba(255, 255, 255, 0.9) !important;
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
  width: 8px;
  height: 8px;
  border-radius: 50%;
  display: inline-block;
}

.status-dot.alta {
  background-color: #FF4D4D;
}

.status-dot.media {
  background-color: #DEB614;
}

.status-dot.baja {
  background-color: #57C200;
}

.card-body {
  padding: 1.5rem;
}

.card-bodyTotal {
  padding: 1.8rem;
}
/* Color Shadows for Top Cards */


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
  align-items: stretch;
  margin-bottom: 0.5rem;
  min-height: 60px;
}

.incidencia-priority-bar {
  width: 6px;
  border-radius: 4px;
  margin-right: 1rem;
}

.incidencia-priority-bar.alta {
  background-color: #FF4D4D;
}

.incidencia-priority-bar.media {
  background-color: #DEB614;
}

.incidencia-priority-bar.baja {
  background-color: #57C200;
}

.incidencia-content {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.5rem 0;
}

.incidencia-date {
  min-width: 100px;
  font-size: 0.875rem;
  color: #000000;
}

.incidencia-description {
  flex: 1;
}

.incidencia-title {
  color: #000000;
  margin-bottom: 0.25rem;
}

.incidencia-subtitle {
  color: #747474;
  font-size: 0.875rem;
}

.incidencia-status {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.875rem;
}

.incidencia-status.pendiente {
  background-color: rgba(255, 193, 7, 0.2);
  color: #ffc107;
}

.incidencia-status.cerrada {
  background-color: rgba(108, 117, 125, 0.2);
  color: #6c757d;
}

.incidencia-status.abierta {
  background-color: rgba(139, 92, 246, 0.2);
  color: #8b5cf6;
}

.incidencia-status.nueva {
  background-color: rgba(255, 193, 7, 0.2);
  color: #ffc107;
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

.total-h2 {
  color: #FFFFFF;
  font-size: 70px;
}
  
.incidenciasabiertos h6{
  color: black;
}
.abiertos-h3{
  color: rgba(116, 116, 116, 1);
}
</style>