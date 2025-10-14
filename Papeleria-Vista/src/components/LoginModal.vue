<template>
  <div v-if="isVisible" class="modal-backdrop">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Iniciar Sesión</h5>
        <button type="button" class="btn-close" @click="$emit('close')"></button>
      </div>
      
      <form @submit.prevent="handleLogin">
        <div class="modal-body">
          <p v-if="errorMessage" class="text-danger small">{{ errorMessage }}</p>
          
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input 
              type="email" 
              class="form-control" 
              id="email" 
              v-model="credentials.email" 
              required
            >
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input 
              type="password" 
              class="form-control" 
              id="password" 
              v-model="credentials.password" 
              required
            >
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button type="submit" class="btn btn-primary" :disabled="isLoading">
            <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
            Ingresar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { login } from '../store.js'; // Importa la función de login del store

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close']);

const credentials = reactive({
  email: '',
  password: '',
});

const isLoading = ref(false);
const errorMessage = ref('');

// Lógica para llamar a la API de Laravel
const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  // Llama a la acción login del store
  const success = await login(credentials.email, credentials.password);
  
  isLoading.value = false;

  if (success) {
    // Si es exitoso, cierra el modal. La Navbar se actualiza automáticamente
    emit('close'); 
    credentials.email = '';
    credentials.password = '';
  } else {
    // Muestra el mensaje de error si las credenciales son incorrectas
    errorMessage.value = 'Credenciales inválidas. Verifica tu email y contraseña.';
  }
};
</script>

<style scoped>
/* Estilos necesarios para el funcionamiento del modal */
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050; 
}
.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>