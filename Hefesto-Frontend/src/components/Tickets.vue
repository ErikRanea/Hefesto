<template>
  <div class="row g-4 mb-4">
      <!-- Header con los paneles -->
      <div class="col-sm-6 col-md-3">
          <div class="card glassmorphic-card colored-shadow-pending create-ticket-card" @click="createTicket">
            <div class="card-bodytotal card-body-same-height d-flex justify-content-between align-items-center">
               <h6 class="mb-0">Crear ticket</h6>
              <img src="../assets/images/icons/crear.svg">
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-md-3">
          <div class="card glassmorphic-card colored-shadow-pending">
              <div class="card-body card-body-same-height">
                <h6>Tickets pendientes</h6>
                  <div class="d-flex justify-content-between align-items-center">
                      <h2>10</h2>
                      <img src="../assets/images/icons/pendientes.svg">
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-md-3">
          <div class="card glassmorphic-card colored-shadow-in-progress">
              <div class="card-body card-body-same-height">
                  <h6>Tickets en curso</h6>
                  <div class="d-flex justify-content-between align-items-center">
                      <h2>10</h2>
                      <img src="../assets/images/icons/curso.svg">
                  </div>
              </div>
          </div>
      </div>
      <div class="col-sm-6 col-md-3">
          <div class="card glassmorphic-card colored-shadow-closed">
              <div class="card-body card-body-same-height">
                  <h6>Tickets cerrados</h6>
                  <div class="d-flex justify-content-between align-items-center">
                      <h2>10</h2>
                      <img src="../assets/images/icons/cerrados.svg">
                  </div>
              </div>
          </div>
      </div>
      <div class="ticket-list">
          <div class="ticket-list-header">
              <span>Fecha</span>
              <div class="priority-legend">
                  <span class="priority-dot alta"></span><span class="priority-text">Alta</span>
                  <span class="priority-dot media"></span><span class="priority-text">Media</span>
                  <span class="priority-dot baja"></span><span class="priority-text">Baja</span>
              </div>
          </div>
          <div v-for="(ticket, index) in tickets" :key="index" class="ticket-item">
              <div class="ticket-date">
                  <span>{{ ticket.date }}</span>
                  <span>{{ ticket.time }}</span>
              </div>
              <div class="ticket-content">
                  <div :class="['priority-marker', ticket.priority]"></div>
                  <div class="ticket-text">
                      <p>{{ ticket.description }}</p>
                      <p>{{ ticket.extraInfo }}</p>
                  </div>
              </div>
              <div class="ticket-status-box">
                  <span :class="['ticket-status',ticket.status.toLowerCase()]">{{ ticket.status }}</span>
              </div>
          </div>
      </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const tickets = ref([
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

const createTicket = () => {
  alert('create ticket')
  // Logic to handle ticket creation (e.g., open a modal)
}
</script>

<style scoped>
.ticket-panel {
  width: 100%;
  padding: 20px;
  background-color: #f8f8f8;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.ticket-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}
.create-ticket-btn {
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
.create-ticket-btn:hover {
  background-color: #f0f0f0;
}
.plus-icon {
  font-size: 1.5rem;
  margin-top: -4px;
}
.ticket-stats {
  display: flex;
  gap: 10px;
}
.ticket-stat-box {
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
.ticket-stat-box.active {
  background-color: #e3fae8;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
.title{
  font-size: 0.8rem;
}
.count{
  font-size: 1.4rem;
  font-weight: bold;
}
.icon{
  font-size: 1.2rem;
}
.ticket-list{
  background-color: #fff;
  border-radius: 5px;
  padding: 10px;
  margin-top: 20px;
}
.ticket-list-header{
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 10px;
  margin-bottom: 10px;
  font-size: 0.8rem;
  color: #666;
}
.priority-legend{
  display: flex;
  gap: 5px;
}
.priority-dot {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  margin-right: 2px;
}
.alta {
  background-color: red;
}
.media {
  background-color: #FFCA28;
}
.baja {
  background-color: green;
}
.ticket-item {
  display: flex;
  padding: 10px;
  gap: 10px;
  align-items: center;
  border-radius: 5px;
}
.ticket-item:hover {
  background-color: #f0f0f0;
}
.ticket-date{
  display: flex;
  flex-direction: column;
  font-size: 0.8rem;
  color: #666;
  min-width: 100px;
}
.ticket-date span:first-child {
  font-weight: 500;
}
.ticket-content{
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  min-width: 300px;
}
.priority-marker {
  width: 4px;
  height: 40px;
  border-radius: 2px;
}
.ticket-text{
  display: flex;
  flex-direction: column;
  font-size: 0.9rem;
  line-height: 1.3;
}
.ticket-status-box{
  display: flex;
  align-items: center;
}
.ticket-status{
  padding: 6px 10px;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
}
.nueva{
  background-color: #e3fae8;
  color: #27ae60;
}
.cerrada {
  background-color: #ffeaea;
  color: #c0392b;
}
.en.curso{
  background-color: #f0e6ff;
  color: #8e44ad;
}
.create-ticket-card{
  cursor: pointer;
    transition: all 0.3s;
}
.create-ticket-card:hover {
  transform: scale(1.02);
    box-shadow: 0 4px 16px 0 rgba(255, 193, 7, 0.7); /* Yellowish for pending */
}
/* Estilos para igualar las alturas de las tarjetas */
.card-body-same-height{
 min-height: 100px;
   display: flex;
  flex-direction: column;
  justify-content: center;
}

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
  padding: 1.7rem;
}

</style>