<template>
  <div v-if="visible" class="popup-overlay">
    <div class="popup-card">
      <h1 v-if="title" class="popup-title">{{ title }}</h1>
      <p v-if="subtitle" class="popup-subtitle">{{ subtitle }}</p>
      <div class="popup-content">
        <slot name="popup-content">
          <slot />
        </slot>
      </div>
      <div class="popup-actions">
        <button v-if="closeButtonText" class="popup-btn glassmorphic-btn cancel-btn" @click="$emit('close')">
          {{ closeButtonText }}
        </button>
        <button
          v-if="actionButtonText"
          class="popup-btn glassmorphic-btn primary create-btn"
          @click="$emit('action')"
        >
          {{ actionButtonText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';
import { ref, onMounted } from 'vue';

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
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-card {
    width: 700px;
    min-height: 300px;
    background: rgba(255, 255, 255, 0.7);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 1.5rem;
    padding: 2rem;
    z-index: 10;
    color: #333;
    display: flex;
    flex-direction: column;
    overflow: hidden; /* Asegura que el contenido no se salga del borde */
}


.popup-title {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  text-align: center;
  color: #222;
}

.popup-subtitle {
  font-size: 1.2rem;
  margin-bottom: 2rem;
  text-align: center;
  color: #444;
}

.popup-content {
  margin-bottom: 2rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  flex: 1;
  justify-content: center;
    overflow-y: auto;  /* Añade scroll vertical si el contenido excede el contenedor */
    padding-right: 10px;
    gap: 1rem;
}


.popup-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 20px;
}

.popup-btn {
  background: none;
  border: none;
  text-align: center;
  font-size: 1rem;
  padding: 0.8rem 1.8rem;
  border-radius: 2rem;
  cursor: pointer;
}

.glassmorphic-btn {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff; /* Text color */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

.glassmorphic-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.popup-btn.primary {
  background-color: rgba(255, 255, 255, 0.8);
  color: #000000;
}

.glassmorphic-btn.cancel-btn{
  color: #000;
    background: rgba(255, 255, 255, 0.8);
}
.glassmorphic-btn.cancel-btn:hover{
    background: rgba(255, 255, 255, 0.9);
}

</style>