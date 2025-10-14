<template>
  <div class="card shadow-sm h-100">
    <div class="card-header bg-light">
      <h5 class="mb-0">🏆 Top Proveedores (Análisis SCM)</h5>
    </div>
    <div class="card-body">
      <div v-if="loading" class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>
      <div v-else-if="error" class="alert alert-danger">
        {{ error }}
      </div>
      <div v-else>
        <ul class="list-group list-group-flush">
          <li v-for="(supplier, index) in topSuppliers" :key="supplier.id" class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <span class="fw-bold">{{ index + 1 }}. {{ supplier.nombre }}</span>
              <small class="d-block text-muted">ID: {{ supplier.id }}</small>
            </div>
            <span class="badge bg-primary rounded-pill">{{ formatCurrency(supplier.total_compras) }}</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="card-footer text-muted text-center">
      Proveedores con mayor volumen de compras.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const topSuppliers = ref([]);
const loading = ref(true);
const error = ref(null);

const API_URL = 'http://127.0.0.1:8000/api/proveedores/top';

const fetchTopSuppliers = async () => {
  loading.value = true;
  try {
    const response = await axios.get(API_URL);
    topSuppliers.value = response.data;
  } catch (err) {
    error.value = 'Error al cargar los proveedores.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const formatCurrency = (value) => {
  const num = parseFloat(value);
  if (isNaN(num)) return '$0.00';
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(num);
};

onMounted(fetchTopSuppliers);
</script>

<style scoped>
.list-group-item {
  border-bottom: 1px solid #eee;
}
</style>
