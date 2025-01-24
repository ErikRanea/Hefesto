import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import './style.css'

const app = createApp(App)

app.use(router)
app.use(Toast)
app.mount('#app')