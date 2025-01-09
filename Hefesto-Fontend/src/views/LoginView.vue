<template>
  <div class="container">
    <form class="form">
      <p id="heading">HEFESTO</p>
      <div class="input-container">
        <input required type="text" name="username" class="input">
        <label class="label">Usuario</label>
      </div>
      <div class="input-container">
        <input required type="password" name="password" class="input">
        <label class="label">Contraseña</label>
      </div>
      <div class="btn">
        <button class="neu-button with-dots">
          <div class="dots_border"></div>
          Iniciar Sesión
        </button>
      </div>
      <div class="forgot-password">
        <a href="#">¿Has olvidado la contraseña?</a>
      </div>
    </form>
  </div>
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
  gap: 20px; /* Increased gap for input containers */
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
  font-size: 1.1em; /* Adjusted label font size */
  padding-left: 15px; /* Adjusted label padding */
  position: absolute;
  top: 15px; /* Adjusted label top position */
  transition: 0.3s;
  pointer-events: none;
  left: 0;
  color: #aaa; /* Muted label color */
}

.input {
  width: 100%; /* Occupy full width of the field */
  height: 55px; /* Increased input height */
  border: none;
  outline: none;
  padding: 15px; /* Adjusted padding */
  padding-left: 15px; /* Adjusted left padding */
  border-radius: 35px; /* Made it as round as the form */
  color: #fff;
  font-size: 1.2em; /* Adjusted input font size */
  background-color: transparent;
  box-shadow: inset 2px 2px 5px rgba(0,0,0,0.8), /* Adjusted shadow */
              inset -2px -2px 5px rgba(255, 255, 255, 0.1); /* Adjusted shadow */
}

.input:focus {
  border: 2px solid transparent;
  color: #fff;
  box-shadow: inset 2px 2px 5px rgba(0,0,0,0.8),
              inset -2px -2px 5px rgba(255, 255, 255, 0.1);
}

.input-container .input:focus ~ .label,
.input-container .input:valid ~ .label {
  transition: 0.3s;
  padding-left: 10px; /* Adjusted label padding on focus/valid */
  transform: translateY(-30px) scale(0.8); /* Adjusted transform and scale */
  color: #bbb; /* Slightly brighter label color on focus/valid */
}

.form .btn {
  display: flex;
  justify-content: center;
  margin-top: 2.7em;
}

.neu-button {
    /* Changed background color to match the input */
    background-color: #171717;
    border-radius: 60px;
    /* Adjusted box-shadow to work with the dark background */
    box-shadow:  5px 5px 12px #0e0e0e, -5px -5px 12px #202020;
    /* Changed text color to be visible on the dark background */
    color: #fff;
    cursor: pointer;
    font-size: 22px;
    padding: 20px 50px;
    transition: all 0.2s ease-in-out;
    border: none; /* Removed the border */
    position: relative; /* To position the dots_border */
    overflow: hidden; /* To hide the overflow of dots_border */
}

.neu-button:hover {
    /* Adjusted hover shadow for the dark background */
    box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020, 3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
}

.neu-button:focus {
    outline: none;
    /* Adjusted focus shadow for the dark background */
    box-shadow: inset 3px 3px 7px #0e0e0e, inset -3px -3px 7px #202020, 3px 3px 7px #0e0e0e, -3px -3px 7px #202020;
}

.neu-button.with-dots {
  padding: 20px 50px; /* Adjust padding to accommodate the border */
}

.dots_border {
  --size_border: calc(100% + 4px); /* Increased size to be slightly outside the button */
  --border_radius: 62px; /* Slightly larger than button's border-radius */

  overflow: hidden;

  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

  width: var(--size_border);
  height: var(--size_border);
  background-color: transparent;

  border-radius: var(--border_radius);
  z-index: -1; /* Place it behind the button content */
}

.dots_border::before {
  content: "";
  position: absolute;
  top: 0; /* Align to the top */
  left: 0; /* Align to the left */
  /* transform: translate(-50%, -50%); */
  transform-origin: left;
  /* transform: rotate(0deg); */

  width: 100%;
  height: 2px; /* Make the line thinner */
  background-color: white;

  /* mask: linear-gradient(transparent 0%, white 120%); */ /* No need for mask anymore */
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
</style>