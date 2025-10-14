<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">Nuestros Productos</h2>

    <div v-if="loading" class="text-center text-primary">
        <span class="spinner-border spinner-border-sm me-2"></span>
        Cargando productos...
    </div>
    
    <div v-else-if="error" class="alert alert-danger text-center">
        Error al cargar productos: {{ error }}
    </div>

    <div v-else class="row g-4">
      
      <div v-for="product in products" :key="product.id" class="col-12 col-sm-6 col-md-4 col-lg-3">
        <ProductCard :product="product" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ProductCard from './ProductCard.vue'; // <-- ¡Componente de Card Principal!

const products = ref([]);
const loading = ref(true);
const error = ref(null);
const API_URL = 'http://127.0.0.1:8000/api';

// Función para cargar los productos aleatorios
const fetchProducts = async () => {
    loading.value = true;
    error.value = null;
    try {
        // LLAMADA CLAVE: Pedimos 5 productos al azar al backend
        const response = await axios.get(`${API_URL}/productos?limit=4`);
        products.value = response.data;
    } catch (err) {
        error.value = 'No se pudieron cargar los productos del servidor.';
        console.error("Error fetching random products:", err);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchProducts);
</script>

<style scoped>
/* No se necesitan estilos aquí, ya que el estilo lo maneja ProductCard.vue */
</style>