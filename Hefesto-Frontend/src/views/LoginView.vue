<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Loader from '../components/Loader.vue';
import ErrorPopup from '../components/ErrorPopup.vue'; // Importa el componente ErrorPopup

const router = useRouter();

// Variable reactiva para controlar el estado del icono
const isPasswordVisible = ref(false);

const inputType = computed(() =>
  isPasswordVisible.value ? 'text' : 'password'
);

const iconClass = computed(() =>
  isPasswordVisible.value ? 'bx bx-show' : 'bx bx-hide'
);

// Alternar visibilidad de La contraseña
const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};

// Variables reactivas para el formulario
const email = ref('');
const password = ref('');
const isLoading = ref(false);

// Variables reactivas para el popup de error
const showErrorPopup = ref(false);
const popupErrorMessage = ref('');

// URL del endpoint de Login
const loginUrl = import.meta.env.VITE_API_AUTH_URL + '/v1/auth/login';

// Función para abrir el popup de error
const openErrorPopup = (message) => {
  popupErrorMessage.value = message;
  showErrorPopup.value = true;
};

// Función para cerrar el popup de error
const closeErrorPopup = () => {
  showErrorPopup.value = false;
  popupErrorMessage.value = '';
};

const login = async () => {
  isLoading.value = true;
  try {
    console.log("Enviando peticion");
    const response = await axios.post(loginUrl, {
      email: email.value,
      password: password.value,
    });

    console.log("Respuesta recibida");  
    const { access_token, token_type } = response.data;
    localStorage.setItem('token', access_token);
    localStorage.setItem('token_type', token_type);

    setTimeout(() => {
      router.push('/home');
      isLoading.value = false;
    }, 1500);

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

<template>
  <div class="container">
    <form class="form" @submit.prevent="login">
      <p id="heading">HEFESTO</p>
      <div class="input-container">
        <input required type="email" name="email" class="input" v-model="email">
        <label class="label">Correo Electrónico</label>
      </div>
      <div class="input-container">
        <input
          required
          :type="inputType"
          name="password"
          class="input"
          v-model="password"
        >
        <label class="label">Contraseña</label>
        <span class="password-toggle-icon" @click="togglePasswordVisibility">
          <i :class="iconClass"></i>
        </span>
      </div>
      <div class="btn">
        <button class="neu-button with-dots" :disabled="isLoading">
          <Loader v-if="isLoading" />
          <span v-else>Iniciar Sesión</span>
        </button>
      </div>
      <div class="forgot-password">
        <a href="#">¿Has olvidado la contraseña?</a>
      </div>
    </form>
  </div>

  <!-- Usa el componente ErrorPopup -->
  <ErrorPopup
    :visible="showErrorPopup"
    :message="popupErrorMessage"
    @close="closeErrorPopup"
  />
</template>

<style scoped>

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding: 3em;
  background-color: #171717;
  border-radius: 35px;
  transition: .4s ease-in-out;
  width: 450px;
}

.form:hover {
  transform: scale(1.05);
  border: 1px solid black;
}

#heading {
  text-align: center;
  margin: 2em 0;
  color: rgb(255, 255, 255);
  font-size: 2.2em;
  font-family: 'aegis1e';
}

.input-container {
  position: relative;
  color: white;
}

.input-container .label {
  font-size: 1.1em;
  padding-left: 15px;
  position: absolute;
  top: 20px;
  transition: 0.3s;
  pointer-events: none;
  left: 0;
  color: #aaa;
}

.input {
  width: 100%;
  height: 55px;
  border: none;
  outline: none;
  padding: 15px;
  padding-left: 15px;
  border-radius: 35px;
  color: #fff;
  font-size: 1.2em;
  background-color: transparent;
  box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.8),
    inset -2px -2px 5px rgba(255, 255, 255, 0.1);
}

.input:focus {
  border: 2px solid transparent;
  color: #fff;
  box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.8),
    inset -2px -2px 5px rgba(255, 255, 255, 0.1);
}

.input-container .input:focus ~ .label,
.input-container .input:valid ~ .label {
  transition: 0.3s;
  padding-left: 10px;
  transform: translateY(-40px) scale(0.8);
  color: #bbb;
}

.form .btn {
  display: flex;
  justify-content: center;
  margin-top: 2.7em;
}

.neu-button {
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

.neu-button:hover {
  box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020, 3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
}

.neu-button:focus {
  outline: none;
  box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020, 3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
}

.neu-button.with-dots {
  padding: 20px 50px;
}

.dots_border {
  --size_border: calc(100% + 4px);
  --border_radius: 62px;

  overflow: hidden;

  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

  width: var(--size_border);
  height: var(--size_border);
  background-color: transparent;

  border-radius: var(--border_radius);
  z-index: -1;
}

.dots_border::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  transform-origin: left;

  width: 100%;
  height: 2px;
  background-color: white;

  animation: rotate 2s linear infinite;
}

@keyframes rotate {
  to {
    transform: rotate(360deg);
  }
}

.forgot-password {
  text-align: center;
  margin-top: 2.2em;
}

.forgot-password a {
  color: #aaa;
  text-decoration: none;
  font-size: 1.1em;
}

.forgot-password a:hover {
  text-decoration: underline;
  color: #ccc;
}

.password-toggle-icon {
  position: absolute;
  top: 0;
  right: 0;
  padding: 15px;
  height: 100%;
  cursor: pointer;
  display: flex;
  align-items: center;
  color: #aaa;
}

.error-message {
  color: #ff4d4d;
  margin-top: 1em;
  text-align: center;
}

.success-message {
  color: #4caf50;
  margin-top: 1em;
  text-align: center;
}
</style>