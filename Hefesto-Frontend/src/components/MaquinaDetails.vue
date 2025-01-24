<template>
    <div class="glass-card text-center">
      <div class="row mb-4">
        <h3>Datos de la Máquina</h3>
      </div>
      <div class="row">
        <div class="col-6">
          <p>Nombre: {{ maquina.nombre_maquina }}</p>
        </div>
        <div class="col-6">
          <p>ID: {{ maquina.id }}</p>
        </div>
        <div class="col-6">
          <p>Número interno: {{ maquina.numero_interno }}</p>
        </div>
        <div class="col-6">
          <p>Tipo: {{ maquina.tipo_maquina }}</p>
        </div>
        <div class="col-6">
          <p>Sección: {{ maquina.id_seccion }}</p>
        </div>
        <div class="col-6">
          <p>Prioridad: {{ maquina.prioridad }}</p>
        </div>
      </div>
    </div>
    <button @click="$emit('close-details')" class="popup-btn primary">Volver</button>
 </template>
 
 <script setup>

const props = defineProps({
  maquina: {
    type: Object,
    required: true,
  },
 });
const emit = defineEmits(['close-details'])

</script>

<style scoped>

.glass-card {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    width: 100%;
    margin-bottom: 1em;
    height: auto;
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
    padding: 2em;
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

  .popup-btn.primary {
    background-color: rgba(255, 255, 255, 0.8);
    color: #000000;
  }

</style>