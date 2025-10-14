<template>
  <div class="modal fade" id="productFormModal" tabindex="-1" aria-labelledby="productFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productFormModalLabel">{{ product ? 'Editar' : 'Agregar' }} Producto</h5>
          <button type="button" class="btn-close" @click="$emit('close')" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" v-model="form.nombre" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" v-model="form.precio" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" rows="3" v-model="form.descripcion"></textarea>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="stock" class="form-label">Stock Inicial</label>
                <input type="number" class="form-control" id="stock" v-model="form.stock" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <select class="form-select" id="proveedor" v-model="form.proveedor_id">
                  <option :value="null">Sin proveedor</option>
                  <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                    {{ supplier.nombre }}
                  </option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({ product: Object });
const emit = defineEmits(['close', 'save']);

const form = ref({});
const suppliers = ref([]);
const API_URL = 'http://127.0.0.1:8000/api';

watch(() => props.product, (newVal) => {
  form.value = newVal ? { ...newVal } : { nombre: '', descripcion: '', precio: 0, stock: 0, proveedor_id: null };
});

const fetchSuppliers = async () => {
  try {
    const response = await axios.get(`${API_URL}/proveedores`);
    suppliers.value = response.data;
  } catch (error) {
    console.error('Error al cargar proveedores:', error);
  }
};

const submitForm = async () => {
  try {
    if (props.product) {
      await axios.put(`${API_URL}/productos/${props.product.id}`, form.value);
    } else {
      await axios.post(`${API_URL}/productos`, form.value);
    }
    emit('save');
    emit('close');
  } catch (error) {
    console.error('Error al guardar el producto:', error);
    alert('No se pudo guardar el producto.');
  }
};

onMounted(fetchSuppliers);
</script>
