<template>
  <div class="container my-5">
    <h2 class="mb-4">Gestión de Inventario (ERP)</h2>
    <p class="text-muted">Administra el catálogo de productos, stock y precios.</p>

    <div class="card shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Productos en Inventario</h5>
        <button class="btn btn-primary btn-sm" @click="openProductModal()">+ Agregar Producto</button>
      </div>
      <div class="card-body">
        <ProductTable 
          :products="products" 
          :loading="loading" 
          :error="error" 
          @edit="openProductModal"
          @delete="deleteProduct"
        />
      </div>
    </div>

    <!-- Modal para el Formulario de Producto -->
    <ProductForm 
      :product="selectedProduct" 
      @close="closeProductModal" 
      @save="fetchProducts"
    />

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Modal } from 'bootstrap';
import ProductTable from './ProductTable.vue';
import ProductForm from './ProductForm.vue';

const products = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedProduct = ref(null);
let productModal = null;

const API_URL = 'http://127.0.0.1:8000/api/productos';

const fetchProducts = async () => {
  loading.value = true;
  try {
    const response = await axios.get(API_URL);
    products.value = response.data;
  } catch (err) {
    error.value = 'Error al cargar los productos.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const openProductModal = (product = null) => {
  selectedProduct.value = product ? { ...product } : null;
  productModal.show();
};

const closeProductModal = () => {
  productModal.hide();
  selectedProduct.value = null;
};

const deleteProduct = async (productId) => {
  if (confirm('¿Estás seguro de eliminar este producto?')) {
    try {
      await axios.delete(`${API_URL}/${productId}`);
      fetchProducts(); // Recargar la lista
    } catch (err) {
      alert('Error al eliminar el producto.');
      console.error(err);
    }
  }
};

onMounted(() => {
  fetchProducts();
  // Inicializar el modal de Bootstrap
  productModal = new Modal(document.getElementById('productFormModal'));
});
</script>
