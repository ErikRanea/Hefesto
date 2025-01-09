<template>
    <div class="container">
      <form class="form">
        <p id="heading">HEFESTO</p>
        <div class="field">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
            </svg>
              <input autocomplete="off" placeholder="Usuario" class="input-field" type="text">
          </div>
          <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
            </svg>
              <input placeholder="Contraseña" class="input-field" type="password">
          </div>
          <div class="btn">
            <button class="neu-button">Iniciar Sesión</button>
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
    gap: 16px;
    padding: 3em;
    background-color: #171717;
    border-radius: 35px;
    transition: .4s ease-in-out;
    width: 450px; /* Increased width */
  }
  
  .form:hover {
    transform: scale(1.05);
    border: 1px solid black;
  }
  
  #heading {
    text-align: center;
    margin: 2em 0;
    color: rgb(255, 255, 255);
    font-size: 2.2em; /* Slightly increased font size */
    font-family: 'aegis1e';
  }
  
  .field {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.2em; /* Increased gap */
    border-radius: 35px;
    padding: 1.1em; /* Increased padding */
    border: none;
    outline: none;
    color: white;
    background-color: #252525;
    box-shadow: inset 2px 5px 10px rgb(5, 5, 5);
  }
  
  .input-icon {
    height: 2em; /* Increased icon size */
    width: 2em; /* Increased icon size */
    fill: white;
  }
  
  .input-field {
    background: none;
    border: none;
    outline: none;
    width: 100%;
    color: #d3d3d3;
    font-size: 1.3em; /* Increased font size */
    padding: 0.5em 0; /* Increased padding */
  }
  
  .form .btn {
    display: flex;
    justify-content: center;
    margin-top: 2.7em; /* Increased margin */
  }
  
  .neu-button {
      background-color: #e0e0e0;
      border-radius: 60px;
      box-shadow: inset 5px 5px 12px #bcbcbc, inset -5px -5px 12px #ffffff;
      color: #4d4d4d;
      cursor: pointer;
      font-size: 22px; /* Increased font size */
      padding: 20px 50px; /* Increased padding */
      transition: all 0.2s ease-in-out;
      border: 2px solid rgb(206, 206, 206);
    }
  
    .neu-button:hover {
      box-shadow: inset 3px 3px 7px #bcbcbc, inset -3px -3px 7px #ffffff, 3px 3px 7px #bcbcbc, -3px -3px 7px #ffffff;
    }
  
    .neu-button:focus {
      outline: none;
      box-shadow: inset 3px 3px 7px #bcbcbc, inset -3px -3px 7px #ffffff, 3px 3px 7px #bcbcbc, -3px -3px 7px #ffffff;
    }
  
  .forgot-password {
    text-align: center;
    margin-top: 2.2em; /* Increased margin */
  }
  
  .forgot-password a {
    color: #aaa;
    text-decoration: none;
    font-size: 1.1em; /* Increased font size */
  }
  
  .forgot-password a:hover {
    text-decoration: underline;
    color: #ccc;
  }
  </style>