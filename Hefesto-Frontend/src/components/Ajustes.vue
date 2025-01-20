<template>
    <div class="container-fluid h-100">
      <div class="row h-100">
        <div class="col-md-6 h-100">
            <div class="d-flex flex-column h-100">
                <button class="glass-card large-card grow" @click="openPopup('perfil')">
                    <div class="card-body text-center">
                        <img src="../assets/images/icons/profile.svg" alt="Foto de perfil" class="img-fluid mb-3 huge-icon">
                        <p class="card-text huge-text">Foto de perfil</p>
                     </div>
                 </button>
                 <button class="glass-card large-card grow" @click="openPopup('contraseña')">
                      <div class="card-body text-center">
                         <img src="../assets/images/icons/password.svg" alt="Contraseña" class="img-fluid mb-3 huge-icon">
                           <p class="card-text huge-text">Contraseña</p>
                     </div>
                 </button>
             </div>
         </div>
        <div class="col-md-6 h-100">
              <button class="glass-card large-card full-height grow" @click="openPopup('fondo')">
                   <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                        <img src="../assets/images/icons/wallpa.svg" alt="Fondo de pantalla" class="img-fluid mb-3 huge-icon">
                          <p class="card-text huge-text">Fondo de pantalla</p>
                      </div>
               </button>
          </div>
      </div>
  
        <GlassmorphicPopup
            :visible="popupVisible"
            :title="popupTitle"
            :subtitle="popupSubtitle"
            :closeButtonText="popupCloseButtonText"
            :actionButtonText="popupActionButtonText"
            @close="closePopup"
            @action="handleAction"
        >
            <div v-if="popupType === 'perfil'" >
              <label for="nombre">Nombre:</label>
              <input type="text" id="nombre" class="form-control" placeholder="Tu nombre">
            </div>
          <div v-else-if="popupType === 'contraseña'">
              <label for="password">Contraseña:</label>
               <input type="password" id="password" class="form-control" placeholder="Tu contraseña">
          </div>
          <div v-else-if="popupType === 'fondo'">
              <textarea id="descripcion" class="form-control" placeholder="Descripción"></textarea>
          </div>
        </GlassmorphicPopup>
    </div>
  </template>
  
  <script setup>
  import GlassmorphicPopup from './GlassmorphicPopup.vue';
  import 'bootstrap/dist/css/bootstrap.css';
  import 'bootstrap/dist/js/bootstrap.js';
  import { ref } from 'vue';
  
  const popupVisible = ref(false);
  const popupType = ref(null)
  const popupTitle = ref(null)
  const popupSubtitle = ref(null)
  const popupCloseButtonText = ref(null)
  const popupActionButtonText = ref(null)
  
  
  const openPopup = (type) => {
      popupVisible.value = true;
      popupType.value = type;
  
      if (type === 'perfil'){
          popupTitle.value = 'Editar perfil'
          popupSubtitle.value = 'Edita tu perfil'
          popupCloseButtonText.value = 'Cerrar'
          popupActionButtonText.value = 'Guardar'
      }
      else if(type === 'contraseña'){
          popupTitle.value = 'Cambiar contraseña'
          popupSubtitle.value = 'Introduce tu nueva contraseña'
          popupCloseButtonText.value = 'Cerrar'
          popupActionButtonText.value = 'Guardar'
      }
      else if (type === 'fondo'){
          popupTitle.value = 'Cambiar fondo'
          popupSubtitle.value = 'Añade la descripción de tu fondo'
          popupCloseButtonText.value = 'Cerrar'
          popupActionButtonText.value = 'Aceptar'
      }
  };
  
  const closePopup = () => {
    popupVisible.value = false;
  };
  
  const handleAction = () => {
      alert(`Action clicked on ${popupType.value}`)
      closePopup();
  }
  </script>
  
  <style scoped>
  .container-fluid {
    padding: 15px;
  }
  
  .glass-card {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 16px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      margin-bottom: 15px;
      width: 100%;
      height: auto;
      padding: 25px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      cursor: pointer;
      transition: transform 0.2s ease-in-out;
  }
  
  .glass-card:hover {
      transform: scale(1.05);
  }
  
  .glass-card .card-text {
      margin-top: 0;
      color: #333;
  }
  
  /* Optional: Style for the icons */
  .glass-card img {
      max-width: 50px;
      height: auto;
  }
  
  .large-card {
    padding: 45px;
  }
  
  .full-height {
    height: 100%;
  }
  
  .grow {
      flex-grow: 1;
  }
  
  .huge-icon {
    max-width: 120px;
  }
  .huge-text {
    font-size: 1.8rem;
  }
  
  </style>