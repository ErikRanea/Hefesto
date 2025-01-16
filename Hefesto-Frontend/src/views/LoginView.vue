<template>
  <section>
    <div class="box">
      <div class="square" style="--i: 0;"></div>
      <div class="square" style="--i: 1;"></div>
      <div class="square" style="--i: 2;"></div>
      <div class="square" style="--i: 3;"></div>
      <div class="square" style="--i: 4;"></div>
      <div class="square" style="--i: 5;"></div>

      <div v-if="!showErrorPopup && !showPasswordRecoveryPopup" class="container">
        <div class="form">
          <h2>Iniciar sesión en Hefesto</h2>
          <form @submit.prevent="login">
            <div class="inputBx input-wide">
              <input type="email" v-model="email" required="required" />
              <span class="floating-label">Email</span>
            </div>
            <div class="inputBx input-wide password">
              <input
                :type="inputType"
                v-model="password"
                required="required"
                id="password-input"
              />
              <span class="floating-label">Contraseña</span>
              <span
                class="password-control"
                @click="togglePasswordVisibility"
              >
                <i :class="iconClass"></i>
              </span>
            </div>
            <div class="inputBx button-container">
              <button class="neu-button" :disabled="isLoading">
                <Loader v-if="isLoading" />
                <span v-if="!isLoading">Iniciar Sesión</span>
              </button>
            </div>
          </form>
          <p @click="openPasswordRecoveryPopup" class="forgot-password-link">
            Has olvidado la contraseña?
          </p>
        </div>
      </div>
      <ErrorPopup
        :visible="showErrorPopup"
        :message="popupErrorMessage"
        @close="closeErrorPopup"
      />
      <ErrorPopup
        :visible="showPasswordRecoveryPopup"
        :message="passwordRecoveryMessage"
         @close="closePasswordRecoveryPopup"
      />
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Loader from '../components/Loader.vue';
import ErrorPopup from '../components/ErrorPopup.vue';

const router = useRouter();

const isPasswordVisible = ref(false);

const inputType = computed(() => (isPasswordVisible.value ? 'text' : 'password'));

const iconClass = computed(() => (isPasswordVisible.value ? 'bx bx-show' : 'bx bx-hide'));

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};

const email = ref('');
const password = ref('');
const isLoading = ref(false);

const showErrorPopup = ref(true); // Mostrar popup de error inicialmente
const popupErrorMessage = ref('Datos de acceso incorrectos. Por favor, verifica tus credenciales.');

const showPasswordRecoveryPopup = ref(false)
const passwordRecoveryMessage = ref('Por favor contacta con un administrador para recuperar tu contraseña.')

const loginUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/login';

const openErrorPopup = (message) => {
    popupErrorMessage.value = message;
    showErrorPopup.value = true;
};

const closeErrorPopup = () => {
  showErrorPopup.value = false;
};


const openPasswordRecoveryPopup = () => {
    showPasswordRecoveryPopup.value = true;
};

const closePasswordRecoveryPopup = () => {
  showPasswordRecoveryPopup.value = false
};


const login = async () => {
  isLoading.value = true;
  try {
    console.log('Enviando peticion');
    const response = await axios.post(loginUrl, {
      email: email.value,
      password: password.value,
    });

    console.log('Respuesta recibida');
    const { access_token, token_type } = response.data;
    localStorage.setItem('token', access_token);
    localStorage.setItem('token_type', token_type);

    router.push('/home');
  } catch (error) {
    isLoading.value = false;
    if (error.response) {
      openErrorPopup(error.response.data.error || 'Error en el inicio de sesión');
    } else {
      openErrorPopup(error.message || 'Error en la conexión');
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=El+Messiri:wght@700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-shadow: border-box;
  font-family: 'El Messiri', sans-serif;
}

body {
  background: #031323;
  overflow: hidden;
}

img {
  width: 32px;
}

section {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
  background-size: 400% 400%;
  animation: gradient 10s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.box {
  position: relative;
}

.box .square {
  position: absolute;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(5px);
  box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 15px;
  animation: square 10s linear infinite;
  animation-delay: calc(-1s * var(--i));
}

@keyframes square {
  0%,
  100% {
    transform: translateY(-20px);
  }
  50% {
    transform: translateY(20px);
  }
}

.box .square:nth-child(1) {
  width: 100px;
  height: 100px;
  top: -15px;
  right: -45px;
}

.box .square:nth-child(2) {
  width: 150px;
  height: 150px;
  top: 105px;
  left: -125px;
  z-index: 2;
}

.box .square:nth-child(3) {
  width: 60px;
  height: 60px;
  bottom: 85px;
  right: -45px;
  z-index: 2;
}

.box .square:nth-child(4) {
  width: 50px;
  height: 50px;
  bottom: 35px;
  left: -95px;
}

.box .square:nth-child(5) {
  width: 50px;
  height: 50px;
  top: -15px;
  left: -25px;
}

.box .square:nth-child(6) {
  width: 85px;
  height: 85px;
  top: 165px;
  right: -155px;
  z-index: 2;
}

.container {
  position: relative;
  padding: 50px;
  width: 400px;
  min-height: 380px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(5px);
  border-radius: 10px;
  box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
}

.container::after {
  content: '';
  position: absolute;
  top: 5px;
  right: 5px;
  bottom: 5px;
  left: 5px;
  border-radius: 5px;
  pointer-events: none;
  background: linear-gradient(
    to bottom,
    rgba(255, 255, 255, 0.1) 0%,
    rgba(255, 255, 255, 0.1) 2%
  );
}

.form {
  position: relative;
  width: 100%;
  height: 100%;
}

.form h2 {
  color: #fff;
  letter-spacing: 2px;
  margin-bottom: 30px;
  text-align: center;
}

.form .inputBx {
  position: relative;
  width: 100%;
  margin-bottom: 20px;
  display: flex; /* Habilitar flexbox */
  justify-content: center; /* Centrar horizontalmente */
}

/* Clase para hacer los inputs más largos */
.form .inputBx.input-wide input {
  width: 90%; /* Inputs más anchos */
}

.form .inputBx input {
  outline: none;
  border: none;
  border: 1px solid rgba(255, 255, 255, 0.2);
  background: rgba(255, 255, 255, 0.2);
  padding: 10px 12px;
  padding-left: 10px;
  border-radius: 15px;
  color: #fff;
  font-size: 16px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.form .inputBx .password-control {
  position: absolute;
  top: 11px;
  right: 10px;
  display: inline-block;
  width: 20px;
  height: 20px;
  cursor: pointer;
  color: #aaa;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form .button-container {
  display: flex;
  justify-content: center;
}

.form .inputBx button {
  background: #fff;
  color: #111;
  width: 90%;
  padding: 10px 20px;
  box-shadow: none;
  letter-spacing: 1px;
  cursor: pointer;
  transition: 1.5s;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 60px;
}

.form .inputBx button:hover {
  background: linear-gradient(
    115deg,
    rgba(0, 0, 0, 0.1),
    rgba(255, 255, 255, 0.25)
  );
  color: #fff;
  transition: 0.5s;
}

.form .inputBx button::placeholder {
  color: #fff;
}

.form .inputBx span.floating-label {
  position: absolute;
  left: 15px; /* Mover el span a la izquierda */
  top: 0px;
  padding: 10px 5px; /* Reducir el padding horizontal */
  display: inline-block;
  color: #fff;
  transition: 0.5s;
  pointer-events: none;
  font-size: 16px; /* Tamaño de fuente inicial */
}

.form .inputBx input:focus ~ span.floating-label,
.form .inputBx input:valid ~ span.floating-label {
  transform: translateY(-25px); /* Mover hacia arriba */
  font-size: 12px; /* Tamaño de fuente más pequeño al enfocar */
  left: 10px; /* Asegurar que el texto se mantenga alineado a la izquierda */
}

.form p {
  color: #fff;
  font-size: 15px;
  margin-top: 5px;
  cursor: pointer;
}

.form .forgot-password-link {
  color: #fff;
  font-size: 15px;
  margin-top: 5px;
  text-align: center;
  cursor: pointer;
}
.forgot-password-link:hover {
  text-decoration: underline;
}

.form p a {
  color: #fff;
}

.form p a:hover {
  background-color: #000;
  background-image: linear-gradient(to right, #434343 0%, black 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.neu-button {
  background-color: #171717;
  border-radius: 60px;
  box-shadow: 5px 5px 12px #0e0e0e, -5px -5px 12px #202020;
  color: #fff;
  cursor: pointer;
  font-size: 18px;
  /* Letra más pequeña */
  padding: 8px 10px;
  transition: all 0.2s ease-in-out;
  border: none;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  /* El botón toma todo el ancho del contenedor */
}

.neu-button:hover {
  box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020,
    3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
}

.neu-button:focus {
  outline: none;
  box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020,
    3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
}

.neu-button.with-dots {
  padding: 20px 50px;
}

</style>