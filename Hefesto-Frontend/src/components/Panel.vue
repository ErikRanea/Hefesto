<template>
  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-md-3">
      <div class="card glassmorphic-card colored-shadow-pending">
        <div class="card-body">
          <h6>Tickets pendientes</h6>
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
          <h6>Tickets en curso</h6>
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
          <h6>Tickets cerrados</h6>
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
          <h6>Total de tickets</h6>
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
            <div class="ticketsabiertos">
              <h6 class="text-muted mb-1">Tickets abiertos</h6>
              <div class="d-flex align-items-baseline">
                <span class="h3 mb-0">20</span>
                <span class="text-muted ms-2">hoy</span>
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

    <!-- Right Column - Tickets List -->
    <div class="col-md-8">
      <div class="card glassmorphic-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Últimos tickets</h5>
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

          <div class="tickets-list">
            <div v-for="(ticket, index) in tickets" :key="index"
                 class="ticket-row">
              <div class="ticket-priority-bar" :class="ticket.priority"></div>
              <div class="ticket-content">
                <div class="ticket-date">
                  <div>{{ ticket.date }}</div>
                  <div>{{ ticket.time }}</div>
                </div>
                <div class="ticket-description">
                  <div class="ticket-title">{{ ticket.title }}</div>
                  <div class="ticket-subtitle">{{ ticket.subtitle }}</div>
                </div>
                <div class="ticket-status">
                  <span class="status-badge" :class="ticket.status.toLowerCase()">
                    {{ ticket.status }}
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

const tickets = [
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
        label: 'Tickets',
        data: [5, 15, 8, 12, 7],
        borderColor: '#8b5cf6',
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
            color: 'rgba(255, 255, 255, 0.1)',
            drawBorder: false
          },
          ticks: {
            color: 'rgba(255, 255, 255, 0.7)',
            padding: 10
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            color: 'rgba(255, 255, 255, 0.7)',
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
      labels: ['15/01', '17/01', '18/01', '19/01', '20/01', '21/01', '22/01'],
      datasets: [{
        label: 'Tickets',
        data: [30, 20, 25, 35, 25, 20, 25],
        backgroundColor: '#8b5cf6'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        }
      }
    }
  })
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
  color: rgba(0, 0, 0, 0.6) !important;
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
  background-color: #dc3545;
}

.status-dot.media {
  background-color: #ffc107;
}

.status-dot.baja {
  background-color: #198754;
}

.card-body {
  padding: 1.5rem;
}

.card-bodyTotal {
  padding: 1.9rem;
}
/* Color Shadows for Top Cards */
.colored-shadow-pending {
  box-shadow: 0 4px 16px 0 #B89B00; /* Yellowish for pending */
}

.colored-shadow-in-progress {
  box-shadow: 0 4px 16px 0 #600484; /* Purple for in progress */
}

.colored-shadow-closed {
  box-shadow: 0 4px 16px 0 #000000; /* Green for closed */
}

.colored-shadow-total {
  box-shadow: 0 4px 16px 0 #FFFFFF; /* Blue for total */
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

.tickets-list {
  margin-top: 1rem;
}

.ticket-row {
  display: flex;
  align-items: stretch;
  margin-bottom: 0.5rem;
  min-height: 60px;
}

.ticket-priority-bar {
  width: 4px;
  border-radius: 2px;
  margin-right: 1rem;
}

.ticket-priority-bar.alta {
  background-color: #dc3545;
}

.ticket-priority-bar.media {
  background-color: #ffc107;
}

.ticket-priority-bar.baja {
  background-color: #198754;
}

.ticket-content {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.5rem 0;
}

.ticket-date {
  min-width: 100px;
  font-size: 0.875rem;
  color: #000000;
}

.ticket-description {
  flex: 1;
}

.ticket-title {
  color: #000000;
  margin-bottom: 0.25rem;
}

.ticket-subtitle {
  color: #747474;
  font-size: 0.875rem;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.875rem;
}

.status-badge.pendiente {
  background-color: rgba(255, 193, 7, 0.2);
  color: #ffc107;
}

.status-badge.cerrada {
  background-color: rgba(108, 117, 125, 0.2);
  color: #6c757d;
}

.status-badge.abierta {
  background-color: rgba(139, 92, 246, 0.2);
  color: #8b5cf6;
}

.status-badge.nueva {
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
  
}


</style>