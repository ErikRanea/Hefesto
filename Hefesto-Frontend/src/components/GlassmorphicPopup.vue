<template>
  <div v-if="visible" class="popup-overlay">
    <div class="popup-card">
      <div class="popup-header">
        <h1 v-if="title" class="popup-title">{{ title }}</h1>
        <button class="close-btn" @click="$emit('close')">×</button>
      </div>
      <p v-if="subtitle" class="popup-subtitle">{{ subtitle }}</p>
      <div class="popup-content">
        <slot name="popup-content">
          <slot />
        </slot>
      </div>
      <div class="popup-actions">
        <slot name="popup-actions">
        <button class="popup-btn" :class="{'default-btn': closeButtonStyle === 'default', 'cancel-btn': closeButtonStyle === 'cancel'}" @click="$emit('close')">
          {{ closeButtonText }}
        </button>
        <button
          v-if="actionButtonText"
          class="popup-btn"
          :class="{'primary-btn': actionButtonStyle === 'primary', 'secondary-btn': actionButtonStyle === 'secondary'}"
          @click="$emit('action')"
        >
          {{ actionButtonText }}
        </button>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
  visible: {
    type: Boolean,
    required: true,
    default: false,
  },
  title: {
    type: String,
    default: '',
  },
  subtitle: {
    type: String,
    default: '',
  },
  closeButtonText: {
    type: String,
    default: 'Cerrar',
  },
  actionButtonText: {
    type: String,
    default: null,
  },
  closeButtonStyle: {
    type: String,
    default: 'default', // Opciones: 'default', 'cancel'
    validator: (value) => ['default', 'cancel'].includes(value),
  },
  actionButtonStyle: {
    type: String,
    default: 'primary', // Opciones: 'primary', 'secondary'
    validator: (value) => ['primary', 'secondary'].includes(value),
  },
});

defineEmits(['close', 'action']);
</script>

<style scoped>
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-card {
  width: 90%;
  max-width: 900px; /* Aumenta el ancho máximo */
  background: rgba(255, 255, 255, 0.7);
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 1.5rem;
  padding: 2rem;
  color: #333;
  display: flex;
  flex-direction: column;
  overflow: auto; /* Permite el crecimiento vertical */
}

.popup-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.popup-title {
  font-size: 2rem;
  color: #222;
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #666;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-btn:hover {
  color: #333;
}

.popup-subtitle {
  font-size: 1.1rem;
  margin-bottom: 1.5rem;
  color: #444;
}

.popup-content {
  margin-bottom: 2rem;
  overflow-x: auto; /* Agrega desplazamiento horizontal si el contenido es demasiado ancho */
  max-height: 70vh; /* Ajusta la altura máxima */
  overflow-y: auto;
  padding-right: 1rem;
}

.popup-content::-webkit-scrollbar {
  width: 8px;
}

.popup-content::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 4px;
}

.popup-content::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 4px;
}

.popup-content::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.3);
}

.popup-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
}

.popup-btn {
  padding: 0.8rem 1.8rem;
  border-radius: 2rem;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
}

.default-btn {
  background: rgba(255, 255, 255, 0.3);
  color: #333;
}

.default-btn:hover {
  background: rgba(255, 255, 255, 0.5);
}

.cancel-btn {
  background: rgba(220, 53, 69, 0.7); /* Rojo */
  color: white;
}

.cancel-btn:hover {
  background: rgba(220, 53, 69, 0.9);
}

.primary-btn {
  background: rgba(96, 4, 132, 1); /* Morado */
  color: white;
}

.primary-btn:hover {
  background: rgba(96, 4, 132, 0.9);
}

.secondary-btn {
  background: rgba(0, 123, 255, 0.7); /* Azul */
  color: white;
}

.secondary-btn:hover {
  background: rgba(0, 123, 255, 0.9);
}
</style>