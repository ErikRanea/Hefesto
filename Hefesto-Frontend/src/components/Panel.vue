<template>
  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card glassmorphic-card">
        <div class="card-body">
          <h6>Tickets pendientes</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2>10</h2>
            <img src="../assets/images/icons/pendientes.svg">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card glassmorphic-card">
        <div class="card-body">
          <h6>Tickets en curso</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2>10</h2>
            <img src="../assets/images/icons/curso.svg">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card glassmorphic-card">
        <div class="card-body">
          <h6>Tickets cerrados</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2>10</h2>
            <img src="../assets/images/icons/cerrados.svg">
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card glassmorphic-card">
        <div class="card-bodytotal">
          <h6>Total de tickets</h6>
          <div class="d-flex justify-content-between align-items-center">
            <h2>10</h2>
            <img src="../assets/images/icons/total.svg">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="row g-4">
    <!-- Left Column - Charts -->
    <div class="col-4">
      <div class="card glassmorphic-card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-4">
            <div>
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
    <div class="col-8">
      <div class="card glassmorphic-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Últimos tickets</h5>
            <button class="btn btn-outline-light btn-sm">Ver todos</button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-borderless">
              <thead>
                <tr>
                  <th>Estado</th>
                  <th>Fecha</th>
                  <th>Descripción</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="i in 8" :key="i">
                  <td><span class="status-dot bg-warning"></span></td>
                  <td>Enero 16, 2025</td>
                  <td>La fresadora tiene ruidos raros pero funciona...</td>
                  <td><span class="badge bg-warning">Pendiente</span></td>
                </tr>
              </tbody>
            </table>
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
  background: rgba(255, 255, 255, 0.1) !important;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.05) !important;
  box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.3);
}

.text-muted {
  color: rgba(255, 255, 255, 0.6) !important;
}

.h3 {
  color: rgba(255, 255, 255, 0.9);
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

.card-bodytotal{
  padding: 1.8rem;
}
</style>