<template>
  <div class="row g-4 mb-4">
      <!-- Header con los paneles -->
      <div class="col-sm-6 col-md-3">
          <div class="card glassmorphic-card colored-shadow-pending create-incidencia-card" @click="createIncidencia">
            <div class="card-bodytotal card-body-same-height d-flex justify-content-between align-items-center">
               <h6 class="mb-0">Crear incidencia</h6>
              <img src="../assets/images/icons/crear.svg">
            </div>
         </div>
      </div>
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
      
      <div class="incidencias-container">
        <div v-for="incidencia in incidencias" :key="incidencia.date + incidencia.time" class="incidencia-item">
          <!-- Restructured layout to match the image -->
          <div class="priority-marker" :class="incidencia.priority"></div>
          <div class="incidencia-content">
            <div class="incidencia-date">
              <span>{{ incidencia.date }}</span>
              <span>{{ incidencia.time }}</span>
            </div>
            <div class="incidencia-text">
              <div>{{ incidencia.description }}</div>
              <small class="text-muted">{{ incidencia.extraInfo }}</small>
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
import { ref } from 'vue';

const incidencias = ref([
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'alta',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'Nueva',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'alta',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'Cerrada',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'media',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'En curso',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'baja',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'Nueva',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'baja',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'En curso',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'media',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'En curso',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'alta',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'En curso',
},
{
  date: 'Enero 16, 2025',
  time: '8:45',
  priority: 'baja',
  description: 'La fresadora tiene ruidos raros pero funciona...',
  extraInfo: 'Ayer a la mañana no tenia ningún ruido extra...',
  status: 'Nueva',
},
])

const createIncidencia = () => {
  alert('create ticket')
  // Logic to handle ticket creation (e.g., open a modal)
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
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
}

.nueva {
  background-color: #E8F5E9;
  color: #2E7D32;
}

.cerrada {
  background-color: #FFEBEE;
  color: #C62828;
}

.en-curso {
  background-color: #E8EAF6;
  color: #3F51B5;
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
  color: rgba(255, 255, 255, 0.6) !important;
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

</style>