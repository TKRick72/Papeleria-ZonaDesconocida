<template>
  <div v-if="loading" class="text-center">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Cargando...</span>
    </div>
  </div>
  <div v-else-if="error" class="alert alert-danger">
    {{ error }}
  </div>
  <div v-else class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Proveedor</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.nombre }}</td>
          <td>{{ product.descripcion }}</td>
          <td>{{ formatCurrency(product.precio) }}</td>
          <td>
            <span :class="stockClass(product.stock)">{{ product.stock }}</span>
          </td>
          <td>{{ product.proveedor?.nombre || 'N/A' }}</td>
          <td>
            <button class="btn btn-outline-secondary btn-sm me-2" @click="$emit('edit', product)">
              <i class="bi bi-pencil"></i> Editar
            </button>
            <button class="btn btn-outline-danger btn-sm" @click="$emit('delete', product.id)">
              <i class="bi bi-trash"></i> Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>


defineProps({
  products: Array,
  loading: Boolean,
  error: String
});

defineEmits(['edit', 'delete']);

const formatCurrency = (value) => {
  const num = parseFloat(value);
  if (isNaN(num)) return '$0.00';
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(num);
};

const stockClass = (stock) => {
  if (stock < 10) return 'badge bg-danger';
  if (stock < 50) return 'badge bg-warning';
  return 'badge bg-success';
};
</script>

<style scoped>
.badge {
  font-size: 0.9em;
}
</style>
