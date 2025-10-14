<template>
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Método de Pago</h5>
    </div>
    <div class="card-body">
      <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="Tarjeta" v-model="paymentMethod" required>
        <label class="form-check-label" for="creditCard">Tarjeta de crédito/débito</label>
      </div>
      
      <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="paymentMethod" id="mercadoPago" value="MercadoPago" v-model="paymentMethod" required>
        <label class="form-check-label" for="mercadoPago">Mercado Pago</label>
      </div>
      
      <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="paymentMethod" id="transferencia" value="Transferencia" v-model="paymentMethod" required>
        <label class="form-check-label" for="transferencia">Transferencia Bancaria</label>
      </div>
      
      <hr>

      <div class="d-grid mt-4">
        <button class="btn btn-lg btn-primary" @click="handleFinalizePurchase">
          Finalizar Compra
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const paymentMethod = ref('Tarjeta'); // Estado del método de pago

const emit = defineEmits(['payment-complete']);

// Lógica para finalizar (simulada)
const handleFinalizePurchase = () => {
    if (paymentMethod.value) {
        emit('payment-complete', paymentMethod.value);
    } else {
        alert('Por favor, selecciona un método de pago.');
    }
};
</script>