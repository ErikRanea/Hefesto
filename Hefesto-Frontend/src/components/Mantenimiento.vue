<template>
    <div class="row g-4 mb-4">
      <div class="col-12">
        <div class="mantenimiento-list glassmorphic-card">
          <div class="mantenimiento-list-header">
            <div>Mantenimientos</div>
            <div class="priority-legend">
              <span><span class="priority-dot alta"></span> Alta</span>
              <span><span class="priority-dot media"></span> Media</span>
              <span><span class="priority-dot baja"></span> Baja</span>
            </div>
          </div>
  
          <div class="mantenimientos-container">
            <div
              v-for="mantenimiento in mantenimientos"
              :key="mantenimiento.date + mantenimiento.time"
              class="mantenimiento-item"
            >
              <!-- Restructured layout to match the image -->
              <div class="priority-marker" :class="mantenimiento.priority"></div>
              <div class="mantenimiento-content">
                <div class="mantenimiento-date">
                  <span>{{ mantenimiento.date }}</span>
                  <span>{{ mantenimiento.time }}</span>
                </div>
                <div class="mantenimiento-text">
                  <div>{{ mantenimiento.machineName }}</div>
                  <small class="text-muted">{{ mantenimiento.internalNumber }}</small>
                </div>
                <div class="mantenimiento-status-box">
                  <span
                    class="mantenimiento-status"
                    :class="mantenimiento.status.toLowerCase().replace(' ', '-')"
                  >
                    {{ mantenimiento.status }}
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
  import { onBeforeMount, ref } from 'vue';
  import axios from 'axios';
import Incidencias from './Incidencias.vue';
  const API_AUTH_URL = import.meta.env.VITE_API_AUTH_URL;
  const ALL_MANTENIMIENTOS_URL = `${API_AUTH_URL}/incidencia/all_mantenimientos`;
  const mantenimientos = ref[null];
  const loading = ref(true);
  
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


  const recogerMantenimiento = async () => {
    try{
      const token = localStorage.getItem('token');
      if(!token){
        throw new Error('No hay token');
      }
      const response = await axios.post(ALL_MANTENIMIENTOS_URL,
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });

      console.log(response.data);
      return response.data;
      
      }
    catch(error){
      console.log(error);
    }
  }

  const cargarMantenimientos = async () => {
    try {
      loading.value = true;
      const mantenimientosData = await recogerMantenimiento();

      mantenimientos.value = mantenimientosData.map((mantenimiento)=> ({
        ...mantenimiento,
        id: mantenimiento.id,
        proridad: obtenerPrioridad(Incidencia.id),


      }));


    }
    catch (error) {
      
    }
  }

  onBeforeMount(async () => {
    await cargarMantenimientos();
  })
  
  </script>
  
  <style scoped>
  .mantenimiento-panel {
    width: 100%;
    padding: 20px;
    background-color: #f8f8f8;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .mantenimiento-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }
  
  .create-mantenimiento-btn {
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
  
  .create-mantenimiento-btn:hover {
    background-color: #f0f0f0;
  }
  
  .plus-icon {
    font-size: 1.5rem;
    margin-top: -4px;
  }
  
  .mantenimiento-stats {
    display: flex;
    gap: 10px;
  }
  
  .mantenimiento-stat-box {
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
  
  .mantenimiento-stat-box.active {
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
  
  .mantenimiento-list {
    background-color: rgba(255, 255, 255, 0.7) !important;
    border-radius: 5px;
    padding: 20px;
    margin-top: 20px;
  }
  
  .mantenimiento-list-header {
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
  
  .mantenimiento-item {
    display: flex;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    transition: all 0.2s ease;
    gap: 15px;
    align-items: flex-start;
  }
  
  .mantenimiento-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }
  
  .mantenimiento-date {
    display: flex;
    flex-direction: column;
    font-size: 0.8rem;
    color: #666;
    min-width: 100px;
    flex-shrink: 0;
  }
  
  .mantenimiento-date span:first-child {
    font-weight: 500;
  }
  
  .mantenimiento-content {
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
  
  .mantenimiento-text {
    display: flex;
    flex-direction: column;
    font-size: 0.9rem;
    line-height: 1.3;
    flex: 1;
    margin-left: 10px;
  }
  
  .mantenimiento-status-box {
    display: flex;
    align-items: center;
  }
  
  .mantenimiento-status {
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
  
  .create-mantenimiento-card {
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .create-mantenimiento-card:hover {
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
  
  .mantenimientos-container {
    max-height: auto;
    overflow-y: auto;
    padding: 0 10px;
  }
  
  /* Add smooth scrollbar for the tickets container */
  .mantenimientos-container::-webkit-scrollbar {
    width: 8px;
  }
  
  .mantenimientos-container::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
  }
  
  .mantenimientos-container::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
  }
  
  .mantenimientos-container::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
  }
  </style>