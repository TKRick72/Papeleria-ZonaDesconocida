import { createApp } from 'vue'
import App from './App.vue'

// 🚨 NUEVAS IMPORTACIONES: 
import router from './router/index.js'; // 1. Importa el archivo de configuración del router
import { auth } from './store.js';     // 2. Importa el estado de autenticación para la guardia

// Importaciones de BootstrapVueNext y Bootstrap CSS
import { createBootstrap } from 'bootstrap-vue-next'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'  


// Importación de tus estilos personalizados
import './assets/styles.css'

const app = createApp(App)

// 🚨 LÍNEA CLAVE: Hace que el estado 'auth' sea globalmente accesible para la guardia de navegación
window.auth = auth; 

// Registra TODOS los componentes (BButton, BCard, etc.)
// Y TAMBIÉN todos los plugins y directivas de JS necesarios.
app.use(createBootstrap())

// 🚨 NUEVO: Registrar y usar el router
app.use(router)

app.mount('#app')