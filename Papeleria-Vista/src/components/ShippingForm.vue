<template>
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">¿A dónde enviamos tu pedido?</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="$emit('shipping-complete', shippingDetails)">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="zip" class="form-label">Código Postal</label>
            <input type="text" class="form-control" id="zip" v-model="shippingDetails.zip" required>
          </div>
          <div class="col-md-6">
            <label for="colony" class="form-label">Colonia</label>
            <select class="form-select" id="colony" v-model="shippingDetails.colony" required>
              <option value="">Selecciona una colonia...</option>
              <option value="pedregal">Pedregal 2</option>
              <option value="misantla">Misantla Centro</option>
            </select>
          </div>
          
          <div class="col-md-8">
            <label for="street" class="form-label">Calle</label>
            <input type="text" class="form-control" id="street" v-model="shippingDetails.street" required>
          </div>
          <div class="col-md-4">
            <label for="number_ext" class="form-label">Número Exterior</label>
            <input type="text" class="form-control" id="number_ext" v-model="shippingDetails.numberExt" required>
          </div>

          <div class="col-md-4">
            <label for="number_int" class="form-label">Número Interior (Opcional)</label>
            <input type="text" class="form-control" id="number_int" v-model="shippingDetails.numberInt">
          </div>
          <div class="col-md-8">
            <label for="reference" class="form-label">Punto de referencia</label>
            <input type="text" class="form-control" id="reference" v-model="shippingDetails.reference" required>
          </div>

          <div class="col-md-12">
            <label for="recipient" class="form-label">Destinatario</label>
            <input type="text" class="form-control" id="recipient" v-model="shippingDetails.recipient" required>
          </div>
          
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-lg btn-success">Continuar al Pago</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { auth } from '../store.js'; // Para obtener el nombre del destinatario por defecto

const emit = defineEmits(['shipping-complete']);

// Estado reactivo para los detalles de envío
const shippingDetails = reactive({
    zip: '', // CAMPO VACÍO
    colony: '', // CAMPO VACÍO
    street: '', // CAMPO VACÍO
    numberExt: '', // CAMPO VACÍO
    numberInt: '', // CAMPO VACÍO
    reference: '', // CAMPO VACÍO
    // El destinatario sigue siendo el nombre del usuario logueado o vacío
    recipient: auth.user ? auth.user.nombre : '', 
});

// Si el usuario está logueado, precargamos el campo Destinatario.
onMounted(() => {
    if (auth.user) {
        shippingDetails.recipient = auth.user.nombre;
    }
});
</script>