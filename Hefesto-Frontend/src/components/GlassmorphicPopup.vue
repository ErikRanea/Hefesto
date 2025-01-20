<template>
    <div v-if="visible" class="popup-overlay">
      <div class="popup-card">
        <h1 v-if="title" class="popup-title">{{ title }}</h1>
        <p v-if="subtitle" class="popup-subtitle">{{ subtitle }}</p>
        <div class="popup-content">
          <slot></slot> <!-- Content slot for form elements or custom content -->
        </div>
        <div class="popup-actions">
          <button v-if="closeButtonText" class="popup-btn" @click="$emit('close')">{{ closeButtonText }}</button>
          <button v-if="actionButtonText" class="popup-btn primary" @click="$emit('action')">{{ actionButtonText }}</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import 'bootstrap/dist/css/bootstrap.css';
  import 'bootstrap/dist/js/bootstrap.js';
  
  
  defineProps({
    visible: {
      type: Boolean,
      required: true,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    subtitle: {
      type: String,
      default: ''
    },
    closeButtonText: {
      type: String,
      default: 'Close'
    },
      actionButtonText: {
          type: String,
          default: null
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
    background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent background */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000; /* High z-index to ensure it's on top */
  }
  
  .popup-card {
      width: 500px; /* Increased width */
      min-height: 300px; /* Increased min-height */
      background: rgba(255, 255, 255, 0.30); /* Changed background alpha to 0.30 */
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 1rem;
    padding: 2rem; /* Increased padding */
    z-index: 10;
    color: whitesmoke;
  }
  
  .popup-title {
    font-size: 2.5rem; /* Increased title font size */
    margin-bottom: 1rem;
    text-align: center;
  }
  
  .popup-subtitle {
    font-size: 1.2rem; /* Increased subtitle font size */
    margin-bottom: 2rem;
    text-align: center;
  }
  
  .popup-content {
    margin-bottom: 2rem; /* Space above the buttons*/
  }
  
  .popup-actions {
    display: flex;
    justify-content: flex-end; /* Align buttons to the end */
    gap: 1rem; /* Space between buttons */
      margin-top: 20px
  }
  
  
  .popup-btn {
    background: none;
    border: none;
    text-align: center;
    font-size: 1rem;
    color: whitesmoke;
    background-color: #fa709a;
    padding: 0.8rem 1.8rem;
    border-radius: 2rem;
    cursor: pointer;
  }
  
  .popup-btn.primary {
      background-color: #a944d2;
  }
  
  </style>