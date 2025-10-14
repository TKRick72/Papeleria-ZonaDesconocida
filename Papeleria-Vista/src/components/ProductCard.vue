<template>
  <div class="card product-card h-100">
    <img 
      :src="imageUrl" 
      class="card-img-top" 
      :alt="product.nombre"
    >
    
    <div class="card-body d-flex flex-column">
      
      <h5 class="card-title fw-bold">{{ product.nombre }}</h5>
      <p class="card-text text-muted small flex-grow-1">{{ product.descripcion.substring(0, 50) }}...</p>
      
      <div class="mt-auto">
        <h4 class="text-primary fw-bolder mb-3">{{ formatPrice(product.precio) }} MXN</h4>
        
        <div class="d-flex align-items-center mb-3">
          <button 
            class="btn btn-outline-secondary btn-sm" 
            @click="quantity = Math.max(1, quantity - 1)"
          >
            <i class="bi bi-dash"></i>
          </button>
          <input type="number" class="form-control form-control-sm text-center mx-1" v-model.number="quantity" min="1">
          <button 
            class="btn btn-outline-secondary btn-sm" 
            @click="quantity++"
          >
            <i class="bi bi-plus"></i>
          </button>
        </div>
        
        <div class="d-grid gap-2">
          <button 
            class="btn btn-primary" 
            @click="handleAddToCart"
            :disabled="quantity === 0"
          >
            <i class="bi bi-cart-plus me-2"></i> Agregar {{ quantity }} al Carrito
          </button>
          
          <button 
            class="btn btn-success" 
            @click="handleBuyNow"
          >
            Comprar Ahora
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { addToCart } from '../store.js'; // Importa la acción del carrito

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const quantity = ref(1);
const router = useRouter();
const API_BASE_URL = 'http://127.0.0.1:8000'; // URL base de tu backend

// Calcula la URL de la imagen dinámicamente
const imageUrl = computed(() => {
    // La imagen_url de la DB es relativa a la carpeta public (ej: "imagenes/lapiz.jpg")
    if (props.product.imagen_url) {
        return `${API_BASE_URL}/${props.product.imagen_url}`;
    }
    // Placeholder si no hay imagen
    return 'ruta/a/placeholder.png';
});

// Formatea el precio a moneda
const formatPrice = (value) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value);
};

// Maneja la adición al carrito
const handleAddToCart = () => {
    if (quantity.value >= 1) {
        // Llama a la acción del store con el producto y la cantidad
        addToCart(props.product, quantity.value);
        // Opcional: Mostrar notificación de éxito
    }
};

// Maneja la compra rápida
const handleBuyNow = () => {
    if (quantity.value >= 1) {
        // Añadir al carrito
        addToCart(props.product, quantity.value);
        // Redirigir a checkout
        router.push('/checkout');
    }
};
</script>

<style scoped>
.product-card {
  transition: transform 0.2s, box-shadow 0.2s;
}
.product-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
.card-img-top {
  height: 250px;
  object-fit: contain; /* Para que la imagen se vea completa */
  padding: 10px;
}
input[type="number"]::-webkit-inner-spin-button, 
input[type="number"]::-webkit-outer-spin-button { 
  -webkit-appearance: none;
  margin: 0;
}
</style>
