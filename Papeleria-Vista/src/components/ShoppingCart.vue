<template>
  <div class="cart-sidebar" :class="{ 'is-visible': isVisible }">
    <div class="p-3">
      <div class="d-flex justify-content-between align-items-center">
        <h3>Mi Carrito</h3>
        <button @click="$emit('toggle-cart')" class="btn btn-light">X</button>
      </div>
      <hr>
      <div v-if="cart.items.length === 0">
        <p>Aún no tienes productos en tu carrito.</p>
      </div>
      <div v-else>
        <div v-for="item in cart.items" :key="item.id" class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <h6>{{ item.nombre }}</h6> 
            <p>{{ item.quantity }} x {{ parseFloat(item.precio).toFixed(2) }} MXN</p>
          </div>
          <button @click="removeFromCart(item.id)" class="btn btn-danger btn-sm">Eliminar</button>
        </div>
        <hr>
        <h5>Total: {{ totalPrice.toFixed(2) }} MXN</h5>
        
        <div class="d-grid mt-3">
          <button 
            class="btn btn-success"
            @click="goToCheckout"
            :disabled="cart.items.length === 0"
          >
            Finalizar Compra
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router'; // 🚨 NUEVO: Importar el router hook
import { cart, removeFromCart } from '../store.js';

defineProps({
  isVisible: {
    type: Boolean,
    required: true
  }
});

defineEmits(['toggle-cart']);

const router = useRouter(); // 🚨 OBTENER LA INSTANCIA DEL ROUTER

const totalPrice = computed(() => {
  // Se mantiene la lógica de cálculo correcta (parseFloat es necesario)
  return cart.items.reduce((total, item) => total + parseFloat(item.precio) * item.quantity, 0);
});

/**
 * Función para cerrar el carrito y navegar a la página de Checkout.
 */
function goToCheckout() {
    if (cart.items.length === 0) {
        alert('Tu carrito está vacío. Por favor, añade productos.');
        return;
    }
    
    // 1. Cierra la barra lateral del carrito
    emit('toggle-cart'); 
    
    // 2. Navega a la ruta protegida de checkout
    router.push({ name: 'Checkout' });
}
</script>

<style scoped>
.cart-sidebar {
    /* Estilos base: Oculto */
    position: fixed;
    top: 0;
    right: 0;
    width: 350px; /* Ancho de la barra lateral */
    height: 100%;
    background-color: white;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
    transform: translateX(100%); /* Ocultar a la derecha */
    transition: transform 0.3s ease-in-out;
    z-index: 1060; /* Mayor que el modal y navbar */
    overflow-y: auto;
}

.cart-sidebar.is-visible {
    transform: translateX(0); /* Mostrar */
}
</style>