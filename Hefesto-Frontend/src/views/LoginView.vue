<template>
  <div class="background">
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
   <span></span>
</div>
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
               <button
                 class="neu-button"
                 :disabled="isLoading"
                 type="submit"
                 :class="{ 'loading-button': isLoading }"
               >
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

const showErrorPopup = ref(false);
const popupErrorMessage = ref('');

const showPasswordRecoveryPopup = ref(false)
const passwordRecoveryMessage = ref('Por favor contacta con un administrador para recuperar tu contraseña.')

const loginUrl = import.meta.env.VITE_API_AUTH_URL + '/auth/login';

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
    if (error.response) {
      openErrorPopup(error.response.data.error || 'Error en el inicio de sesión');
    } else {
      openErrorPopup(error.message || 'Error en la conexión');
    }
  } finally {
     isLoading.value = false;
  }
};
</script>

<style lang="scss" scoped>

$background-color: #031323;
$white-01: rgba(255, 255, 255, 0.1);
$white-015: rgba(255, 255, 255, 0.15);
$white-02: rgba(255, 255, 255, 0.2);
$black-005: rgba(0, 0, 0, 0.05);
$black-01: rgba(0, 0, 0, 0.1);
$black-02: rgba(0, 0, 0, 0.2);
$hover-gradient-1: rgba(0, 0, 0, 0.1);
$hover-gradient-2: rgba(255, 255, 255, 0.25);
$neu-shadow-1: #0e0e0e;
$neu-shadow-2: #202020;

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'helvetica';
}

body {
  background: $background-color;
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

  .square {
      position: absolute;
      background: $white-01;
      backdrop-filter: blur(5px);
      box-shadow: 0 25px 45px $black-01;
      border: 1px solid $white-015;
      border-radius: 15px;
      animation: square 10s linear infinite;
      animation-delay: calc(-1s * var(--i));

    &:nth-child(1) {
        width: 100px;
        height: 100px;
        top: -15px;
        right: -45px;
      }

    &:nth-child(2) {
        width: 150px;
        height: 150px;
        top: 105px;
        left: -125px;
        z-index: 2;
      }
    &:nth-child(3) {
      width: 60px;
      height: 60px;
      bottom: 85px;
      right: -45px;
      z-index: 2;
    }
    &:nth-child(4) {
      width: 50px;
      height: 50px;
      bottom: 35px;
      left: -95px;
    }
    &:nth-child(5) {
      width: 50px;
      height: 50px;
      top: -15px;
      left: -25px;
    }
    &:nth-child(6) {
      width: 85px;
      height: 85px;
      top: 165px;
      right: -155px;
      z-index: 2;
    }
  }
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

.container {
  position: relative;
  padding: 50px;
  width: 400px;
  min-height: 380px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: $white-01;
  backdrop-filter: blur(5px);
  border-radius: 10px;
  box-shadow: 0 25px 45px $black-02;

  &::after {
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
      $white-01 0%,
      $white-01 2%
    );
  }
}

.form {
  position: relative;
  width: 100%;
  height: 100%;

  h2 {
    color: #fff;
    letter-spacing: 2px;
    margin-bottom: 30px;
    text-align: center;
  }

  .inputBx {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
    display: flex;
    justify-content: center;

    &.input-wide {
        input {
            width: 90%;
        }
    }


    input {
      outline: none;
      border: none;
      border: 1px solid $white-02;
      background: $white-02;
      padding: 10px 12px;
      padding-left: 10px;
      border-radius: 15px;
      color: #fff;
      font-size: 16px;
      box-shadow: 0 5px 15px $black-005;
    }

    .password-control {
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
  }

    .button-container {
        display: flex;
        justify-content: center;
    }

    .inputBx button {
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

        &:hover {
            background: linear-gradient(
              115deg,
              $hover-gradient-1,
              $hover-gradient-2
            );
            color: #fff;
            transition: 0.5s;
        }


      &::placeholder {
        color: #fff;
      }
    }



    .inputBx span.floating-label {
      position: absolute;
      left: 15px;
      top: 0px;
      padding: 10px 5px;
      display: inline-block;
      color: #fff;
      transition: 0.5s;
      pointer-events: none;
      font-size: 16px;
    }


    .inputBx input:focus ~ span.floating-label,
    .inputBx input:valid ~ span.floating-label {
      transform: translateY(-25px);
      font-size: 12px;
      left: 10px;
    }

  p {
      color: #fff;
      font-size: 15px;
      margin-top: 5px;
      cursor: pointer;
    }


    .forgot-password-link {
        color: #fff;
        font-size: 15px;
        margin-top: 5px;
        text-align: center;
        cursor: pointer;
      &:hover {
          text-decoration: underline;
      }
    }


  p a {
    color: #fff;
    &:hover {
        background-color: #000;
        background-image: linear-gradient(to right, #434343 0%, black 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
  }
}


.neu-button {
    background-color: #171717;
    border-radius: 60px;
    box-shadow: 5px 5px 12px $neu-shadow-1, -5px -5px 12px $neu-shadow-2;
    color: #fff;
    cursor: pointer;
    font-size: 18px;
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
    &:hover {
      box-shadow: inset 3px 3px 7px $neu-shadow-1, inset -3px -3px 7px $neu-shadow-2,
        3px 3px 7px $neu-shadow-1, -3px -3px 7px $neu-shadow-2;
    }
    &:focus {
      outline: none;
      box-shadow: inset 3px 3px 7px $neu-shadow-1, inset -3px -3px 7px $neu-shadow-2,
        3px 3px 7px $neu-shadow-1, -3px -3px 7px $neu-shadow-2;
    }

  &.with-dots {
    padding: 20px 50px;
  }
}

.loading-button {
    background: linear-gradient(
      115deg,
      $hover-gradient-1,
      $hover-gradient-2
    );
    color: #fff;
    transition: 0.5s;
}
</style>