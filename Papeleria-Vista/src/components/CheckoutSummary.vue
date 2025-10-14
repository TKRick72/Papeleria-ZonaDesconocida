<template>
  <div class="card shadow-sm h-100">
    <div class="card-header bg-light">
      <h5 class="mb-0">Resumen de Compra</h5>
    </div>
    <div class="card-body">
      <div v-if="cart.items.length === 0">
        <p class="text-center text-muted">No hay productos en el carrito.</p>
      </div>
      <div v-else>
        <div v-for="item in cart.items" :key="item.id" class="d-flex justify-content-between align-items-center mb-2 item-summary">
          <div class="d-flex align-items-center">
            
            <div class="item-placeholder me-2 bg-light d-flex align-items-center justify-content-center">
              <i class="bi bi-box-seam text-secondary"></i>
            </div>

            <div>
              <p class="mb-0 fw-bold">{{ item.nombre }}</p>
              <p class="text-muted small">Cantidad: {{ item.quantity }}</p>
            </div>
          </div>
          <span class="fw-bold">{{ formatPrice(parseFloat(item.precio) * item.quantity) }}</span>
        </div>
        <hr>
        <div class="total-amount">
          <h5 class="mb-0">Total: {{ formatPrice(total) }}</h5>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { cart } from '../store.js';

const total = computed(() => {
  return cart.items.reduce((sum, item) => sum + (parseFloat(item.precio) * item.quantity), 0);
});

const formatPrice = (value) => {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value);
};
</script>

<style scoped>
.item-placeholder {
  width: 40px;
  height: 40px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.bi-box-seam {
  font-size: 1.2rem;
}
.total-amount {
  font-weight: bold;
  text-align: right;
  padding-top: 10px;
}
</style>
