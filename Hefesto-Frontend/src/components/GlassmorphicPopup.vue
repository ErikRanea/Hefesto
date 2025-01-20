<template>
    <div v-if="visible" class="popup-overlay">
      <div class="popup-card">
        <h1 v-if="title" class="popup-title">{{ title }}</h1>
        <p v-if="subtitle" class="popup-subtitle">{{ subtitle }}</p>
        <div class="popup-content">
           <slot name="popup-content">
               <div v-if="imageUrl" class="profile-image-container">
                   <img :src="imageUrl" alt="User Profile" class="rounded-circle profile-image" />
                </div>
                <div v-else class="rounded-circle bg-secondary d-flex justify-content-center align-items-center default-profile-image" style="width: 150px; height: 150px;">
                  <i class="bi bi-person-circle text-white" style="font-size: 5rem;"></i>
                </div>
                <input type="file" id="profile-upload" class="form-control mt-2" @change="handleFileUpload" accept="image/*">
           </slot>
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
  import { ref, onMounted } from 'vue';
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
     imageUrl: {
         type: String,
         default: null,
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
  
  const emit = defineEmits(['close', 'action', 'image-changed']);
  
  const handleFileUpload = (event) => {
    const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
          reader.onload = (e) => {
              emit('image-changed', e.target.result);
            };
          reader.readAsDataURL(file);
      }
  };
  
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
    background: rgba(255, 255, 255, 0.30);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 1rem;
    padding: 2rem;
    z-index: 10;
    color: whitesmoke;
  }
  
  .popup-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    text-align: center;
  }
  
  .popup-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    text-align: center;
  }
  
  .popup-content {
    margin-bottom: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center
  }
  
  .popup-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
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
  
  
  .profile-image-container {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
  }
  
  .profile-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
  }
  
  .default-profile-image {
      width: 150px;
      height: 150px
  }
  
  #profile-upload {
      width: 100%;
  }
  
  </style>