<template>
  <div class="container my-5">
    <div class="text-center">
      <h3>Suscríbete a nuestro boletín</h3>
      <p>Recibe promociones y noticias exclusivas en tu correo.</p>
      <form @submit.prevent="subscribe">
        <div class="input-group mb-3 mx-auto" style="max-width: 400px;">
          <input type="email" class="form-control" placeholder="Tu correo electrónico" v-model="email">
          <b-button type="submit" variant="primary">Suscribirse</b-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const email = ref('');

function subscribe() {
  if (email.value) {
    alert(`Gracias por suscribirte con el correo: ${email.value}`);
    email.value = '';
  }
}
</script>
