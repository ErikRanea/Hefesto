<template>
  <div class="navbar">
    <div class="profile">
      <div class="imgbox">
        <img :src="profilePictureUrl" alt="" />
      </div>
      <div class="heading">
        <h3 class="title">{{ userName }} {{ userLastName }}</h3>
        <h4 class="label">
          {{ userRole }}
        </h4>
      </div>
    </div>
    <!-- navitems -->
    <ul class="nav-items">
      <template v-if="userRole === 'administrador'">
        <li text-data="maquinas">
          <a href="#">
            <i class="uil uil-server"></i>
            <span>MAQUINAS</span>
          </a>
        </li>
        <li text-data="tickets">
          <a href="#">
            <i class="uil uil-ticket"></i>
            <span>TICKETS</span>
          </a>
        </li>
        <li text-data="mantenimiento">
          <a href="#">
            <i class="uil uil-wrench"></i>
            <span>MANTENIMIENTO</span>
          </a>
        </li>
        <li text-data="administracion">
          <a href="#">
            <i class="uil uil-setting"></i>
            <span>ADMINISTRACION</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="uil uil-setting"></i>
            <span>AJUSTES</span>
          </a>
        </li>
      </template>
      <template v-else-if="userRole === 'tecnico'">
        <li text-data="maquinas">
          <a href="#">
            <i class="uil uil-server"></i>
            <span>maquinas</span>
          </a>
        </li>
        <li text-data="tickets">
          <a href="#">
            <i class="uil uil-ticket"></i>
            <span>tickets</span>
          </a>
        </li>
        <li text-data="mantenimiento">
          <a href="#">
            <i class="uil uil-wrench"></i>
            <span>mantenimiento</span>
          </a>
        </li>
        <li text-data="mis tickets">
          <a href="#">
            <i class="uil uil-clipboard-notes"></i>
            <span>mis tickets</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="uil uil-setting"></i>
            <span>ajustes</span>
          </a>
        </li>
      </template>
      <template v-else-if="userRole === 'operario'">
        <li text-data="maquinas">
          <a href="#">
            <i class="uil uil-server"></i>
            <span>maquinas</span>
          </a>
        </li>
        <li text-data="tickets">
          <a href="#">
            <i class="uil uil-ticket"></i>
            <span>tickets</span>
          </a>
        </li>
        <li text-data="mantenimiento">
          <a href="#">
            <i class="uil uil-wrench"></i>
            <span>mantenimiento</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="uil uil-setting"></i>
            <span>ajustes</span>
          </a>
        </li>
      </template>
      <template v-else>
        <li>
          <a href="#">
            <i class="uil uil-setting"></i>
            <span>ajustes</span>
          </a>
        </li>
      </template>
    </ul>
    <ul class="logout-list">
         <li>
            <a href="#" @click.prevent="$emit('logout')">
              <i class="uil uil-signout"></i>
              <span>cerrar sesion</span>
            </a>
          </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Sidebar',
  props: {
    userRole: {
      type: String,
      required: true,
    },
    userName: {
      type: String,
      required: true,
    },
    userLastName: {
      type: String,
      required: true,
    },
    userPicture: {
      type: String,
      required: true,
    },
  },
  computed: {
    profilePictureUrl() {
      return `../src/assets/images/userPicture/${this.userPicture}`;
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Helvetica', sans-serif;
}

.navbar {
  position: relative;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  padding: 20px;
  width: 270px;
  top: 40px;
  left: 20px;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  height: calc(100vh - 100px);
  display: flex;
  flex-direction: column;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}

.profile {
  position: relative;
  display: flex;
  gap: 15px;
  align-items: center;
  padding: 20px 0;
}

.profile::after {
  position: absolute;
  content: '';
  width: 100%;
  height: 1px;
  background: rgba(255, 255, 255, 0.2);
  left: 0;
  bottom: 0;
}

.profile .imgbox {
  position: relative;
  height: 60px;
  width: 60px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  overflow: hidden;
  background: rgba(255, 255, 255, 0.1);
}

.imgbox img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.heading {
  color: #fff;
}

.heading h3 {
  font-size: 1.1em;
  font-weight: 500;
  margin-bottom: 4px;
}

.heading h4 {
  opacity: 0.7;
  font-size: 0.9em;
  font-weight: 400;
  text-transform: capitalize;
}

.nav-items {
  margin-top: 30px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.logout-list {
    margin-bottom: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

ul li {
  list-style: none;
}

ul li a {
  color: #fff;
  font-size: 0.95em;
  font-weight: 400;
  display: flex;
  align-items: center;
  padding: 0 20px;
  height: 45px;
  text-decoration: none;
  text-transform: capitalize;
  border-radius: 8px;
  transition: all 0.3s ease;
}

ul li a:hover {
  background: rgba(255, 255, 255, 0.1);
}

ul li a i {
  font-size: 1.2em;
  margin-right: 12px;
}

@media screen and (max-width: 768px) {
  .navbar {
    width: 80px;
    padding: 15px 10px;
    top: 10px;
    left: 10px;
    margin-bottom: 10px;
    height: calc(100vh - 20px);
    border-radius: 10px 10px 0 0;
  }

  .profile {
    flex-direction: column;
    gap: 10px;
  }

  .heading {
    display: none;
  }

  ul li a {
    justify-content: center;
    padding: 0;
  }

  ul li a span {
    display: none;
  }

  ul li a i {
    margin: 0;
    font-size: 1.4em;
  }

  ul li {
    position: relative;
  }

  ul li::before {
    position: absolute;
    content: attr(text-data);
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    font-size: 0.85em;
    font-weight: 500;
    top: 50%;
    left: 85px;
    transform: translateY(-50%);
    border-radius: 6px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    white-space: nowrap;
    pointer-events: none;
  }

  ul li:hover::before {
    opacity: 1;
    visibility: visible;
  }
}
</style>