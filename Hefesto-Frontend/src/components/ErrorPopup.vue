<template>
    <div v-if="visible" class="error-popup-container">
      <div class="popup-overlay"></div>
      <form class="form">
        <p id="heading">Error</p>
        <div class="error-message" style="color: white; text-align: center;">
          {{ message }}
        </div>
        <div class="btn">
          <button type="button" class="neu-button" @click="$emit('close')">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  defineProps({
    visible: Boolean,
    message: String,
  });
  
  defineEmits(['close']);
  </script>
  
  <style scoped>
  .error-popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000; /* Asegura que el popup esté encima de todo */
  }
  
  .popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semi-transparente */
  }
  
  .error-popup-container .form {
    /* Puedes ajustar los estilos del formulario dentro del popup si es necesario */
    z-index: 1001; /* Asegura que el formulario esté encima del overlay */
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 3em;
    background-color: #171717;
    border-radius: 35px;
    transition: .4s ease-in-out;
    width: 450px;
  }
  
  .error-popup-container .form:hover {
    transform: scale(1.05);
    border: 1px solid black;
  }
  
  .error-popup-container #heading {
    text-align: center;
    margin: 2em 0;
    color: rgb(255, 255, 255);
    font-size: 2.2em;
    font-family: 'Helvetica';
  }
  
  .error-popup-container .btn {
    display: flex;
    justify-content: center;
    margin-top: 1em; /* Ajusta el margen del botón en el popup */
  }
  
  .error-popup-container .neu-button {
    background-color: #171717;
    border-radius: 60px;
    box-shadow: 5px 5px 12px #0e0e0e, -5px -5px 12px #202020;
    color: #fff;
    cursor: pointer;
    font-size: 22px;
    padding: 20px 50px;
    transition: all 0.2s ease-in-out;
    border: none;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center; /* Centrar el Loader y el texto */
    gap: 8px; /* Espacio entre el Loader y el texto */
  }
  
  .error-popup-container .neu-button:hover {
    box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020, 3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
  }
  
  .error-popup-container .neu-button:focus {
    outline: none;
    box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020, 3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
  }
  
  .error-popup-container .error-message {
    color: #ff4d4d;
    margin-top: 1em;
    text-align: center;
  }
  </style>