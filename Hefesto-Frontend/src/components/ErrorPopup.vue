<template>
  <div v-if="visible" class="container">
    <div class="card">
      <h1 class="title">Error</h1>
      <p class="subtitle" style="text-align:center">{{ message }}</p>
      <button class="btn" @click="$emit('close')">Cerrar</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  visible: Boolean,
  message: String,
});

defineEmits(['close']);
</script>

<style scoped>
/* Estilos proporcionados por el usuario */
* {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
	font-family: "Nunito", sans-serif;
}
body {
  background: #1f1f47;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;

}

.card {
  width: 400px;
  min-height: 250px;
  background: rgba( 255, 255, 255, 0.15 );
  box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
  backdrop-filter: blur( 18px );
  -webkit-backdrop-filter: blur( 18px );
  border: 1px solid rgba( 255, 255, 255, 0.18 );
  border-radius: 1rem;
  padding: 1.5rem;
  z-index: 10;
  color: whitesmoke;
}

.title {
  font-size: 2.2rem;
  margin-bottom: 1rem;
  text-align: center;
}

.subtitle {
  font-size: 1rem;
  margin-bottom: 2rem;
  text-align: center;

}

.btn {
  background: none;
  border: none;
  text-align: center;
  font-size: 1rem;
  color: whitesmoke;
  background-color: #fa709a;
  padding: 0.8rem 1.8rem;
  border-radius: 2rem;
  cursor: pointer;
    margin: auto;
    display: block;
    margin-top: 20px
}
</style>