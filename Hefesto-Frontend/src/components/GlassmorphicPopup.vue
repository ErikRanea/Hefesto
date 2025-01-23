<template>
  <div v-if="visible" class="popup-overlay">
    <div class="popup-card">
      <div class="popup-header">
        <h1 v-if="title" class="popup-title">{{ title }}</h1>
        <button class="close-btn" @click="$emit('close')">&times;</button>
      </div>
      <p v-if="subtitle" class="popup-subtitle">{{ subtitle }}</p>
      <div class="popup-content">
        <slot name="popup-content">
          <slot />
        </slot>
      </div>
      <div class="popup-actions">
        <button v-if="closeButtonText" class="popup-btn cancel-btn" @click="$emit('close')">
          {{ closeButtonText }}
        </button>
        <button
          v-if="actionButtonText"
          class="popup-btn primary-btn"
          @click="$emit('action')"
        >
          {{ actionButtonText }}
        </button>
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
  max-width: 700px;
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
  max-height: 60vh;
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

.cancel-btn {
  background: rgba(255, 255, 255, 0.3);
  color: #333;
}

.cancel-btn:hover {
  background: rgba(255, 255, 255, 0.5);
}

.primary-btn {
  background: rgba(0, 123, 255, 0.7);
  color: white;
}

.primary-btn:hover {
  background: rgba(0, 123, 255, 0.9);
}
</style>