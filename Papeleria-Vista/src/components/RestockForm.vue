<template>
  <div class="card shadow-sm mt-4">
    <div class="card-header bg-light">
      <h5 class="mb-0">Registrar Compra de Inventario (SCM)</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="submitRestock">
        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="product" class="form-label">Producto</label>
            <select class="form-select" v-model="restock.producto_id" required>
              <option disabled value="">Seleccione un producto</option>
              <option v-for="product in products" :key="product.id" :value="product.id">
                {{ product.nombre }}
              </option>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label for="quantity" class="form-label">Cantidad</label>
            <input type="number" class="form-control" v-model.number="restock.cantidad" placeholder="Ej: 100" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="cost" class="form-label">Costo Total de la Compra</label>
            <input type="number" step="0.01" class="form-control" v-model.number="restock.costo_total" placeholder="Ej: 500.00" required>
          </div>
        </div>
        <button type="submit" class="btn btn-success">Registrar Compra</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const products = ref([]);
const restock = ref({
  producto_id: '',
  cantidad: null,
  costo_total: null
});

const API_URL = 'http://127.0.0.1:8000/api';

const fetchProducts = async () => {
  try {
    const response = await axios.get(`${API_URL}/productos`);
    products.value = response.data;
  } catch (error) {
    console.error('Error al cargar productos:', error);
  }
};

const submitRestock = async () => {
  if (!restock.value.producto_id || !restock.value.cantidad || !restock.value.costo_total) {
    alert('Por favor, complete todos los campos.');
    return;
  }
  try {
    await axios.post(`${API_URL}/compras`, restock.value);
    alert('¡Compra registrada y stock actualizado!');
    // Reset form
    restock.value = { producto_id: '', cantidad: null, costo_total: null };
    // Opcional: emitir un evento para que el padre actualice la lista de productos
  } catch (error) {
    console.error('Error al registrar la compra:', error);
    alert('No se pudo registrar la compra.');
  }
};

onMounted(fetchProducts);
</script>
