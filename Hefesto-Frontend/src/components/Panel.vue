<template>
    <div class="ticket-container p-3 bg-light rounded-3">
      <!-- Navigation Tabs -->
      <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
          <a class="nav-link" :class="{ active: activeTab === 'entrantes' }" href="#" @click.prevent="activeTab = 'entrantes'">
            Entrantes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" :class="{ active: activeTab === 'realizados' }" href="#" @click.prevent="activeTab = 'realizados'">
            Realizados
          </a>
        </li>
      </ul>
  
      <!-- Priority Filters -->
      <div class="priority-filters mb-4">
        <ul class="nav nav-underline">
          <li class="nav-item">
            <a class="nav-link" :class="{ active: activePriority === 'todos' }" href="#" @click.prevent="activePriority = 'todos'">
              Todos
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: activePriority === 'urgente' }" href="#" @click.prevent="activePriority = 'urgente'">
              Urgente
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: activePriority === 'medio' }" href="#" @click.prevent="activePriority = 'medio'">
              Medio
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: activePriority === 'baja' }" href="#" @click.prevent="activePriority = 'baja'">
              Baja
            </a>
          </li>
        </ul>
      </div>
  
      <!-- Ticket List -->
      <div class="ticket-list">
        <div v-for="ticket in filteredTickets" :key="ticket.id" class="ticket-item p-3 mb-2 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center flex-grow-1">
            <span class="ticket-text">{{ ticket.description }}</span>
            <span class="priority-indicator ms-2">
              <span class="dot"></span>
              {{ ticket.priority }}
            </span>
          </div>
          <div class="d-flex align-items-center">
            <div class="form-check me-3">
              <input 
                type="checkbox" 
                class="form-check-input" 
                :id="'ticket-' + ticket.id"
                v-model="ticket.checked"
              >
            </div>
            <button class="btn btn-enter px-3">Entrar</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  
  const activeTab = ref('entrantes')
  const activePriority = ref('todos')
  
  const tickets = ref([
    { id: 1, description: 'Se ha roto la fresadora en la sección de mecanica', priority: 'urgente', checked: false },
    { id: 2, description: 'Se ha roto la fresadora en la sección de mecanica', priority: 'urgente', checked: false },
    { id: 3, description: 'Se ha roto la fresadora en la sección de mecanica', priority: 'urgente', checked: false },
    { id: 4, description: 'Se ha roto la fresadora en la sección de mecanica', priority: 'urgente', checked: false },
    { id: 5, description: 'Se ha roto la fresadora en la sección de mecanica', priority: 'urgente', checked: false },
  ])
  
  const filteredTickets = computed(() => {
    if (activePriority.value === 'todos') return tickets.value
    return tickets.value.filter(ticket => ticket.priority === activePriority.value)
  })
  </script>
  
  <style scoped>
  .ticket-container {
    background: linear-gradient(145deg, #e6e6e6, #d5d5d5) !important;
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .ticket-item {
    background: linear-gradient(145deg, #f5f5f5, #e6e6e6);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 6px;
  }
  
  .nav-tabs .nav-link,
  .nav-underline .nav-link {
    color: #666;
    border: none;
  }
  
  .nav-tabs .nav-link.active,
  .nav-underline .nav-link.active {
    color: #333;
    font-weight: 500;
    border-bottom: 2px solid #333;
  }
  
  .priority-indicator {
    display: flex;
    align-items: center;
    color: #666;
    font-size: 0.9rem;
  }
  
  .dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: #dc3545;
    border-radius: 50%;
    margin-right: 5px;
  }
  
  .form-check-input {
    width: 1.2rem;
    height: 1.2rem;
    border-radius: 50%;
    border: 2px solid #666;
    cursor: pointer;
  }
  
  .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
  }
  
  .btn-enter {
    background: linear-gradient(145deg, #f5f5f5, #e6e6e6);
    border: 1px solid rgba(0, 0, 0, 0.1);
    color: #666;
    border-radius: 20px;
    padding: 0.25rem 1rem;
    font-size: 0.9rem;
    transition: all 0.2s;
  }
  
  .btn-enter:hover {
    background: linear-gradient(145deg, #e6e6e6, #d5d5d5);
    color: #333;
  }
  
  .ticket-text {
    color: #333;
    font-size: 0.95rem;
  }
  
  /* Custom scrollbar for webkit browsers */
  ::-webkit-scrollbar {
    width: 8px;
  }
  
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }
  
  ::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
  }
  
  ::-webkit-scrollbar-thumb:hover {
    background: #666;
  }
  </style>