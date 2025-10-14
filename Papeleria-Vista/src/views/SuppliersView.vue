<template>
  <div class="container my-5">
    <h2 class="mb-4">Gestión de Proveedores (SCM)</h2>
    <p class="text-muted">Administra tus proveedores y órdenes de compra.</p>
    
    <div class="card shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Proveedores Registrados</h5>
        <button class="btn btn-primary btn-sm" @click="openModal()">+ Agregar Proveedor</button>
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
        <div v-else class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="supplier in suppliers" :key="supplier.id">
                <td>{{ supplier.id }}</td>
                <td>{{ supplier.nombre }}</td>
                <td>{{ supplier.contacto }}</td>
                <td>{{ supplier.telefono }}</td>
                <td>{{ supplier.direccion }}</td>
                <td>
                  <button class="btn btn-outline-secondary btn-sm me-2" @click="openModal(supplier)">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <button class="btn btn-outline-success btn-sm me-2" @click="openRestockModal(supplier)">
                    <i class="bi bi-cart-plus"></i> Re-stock
                  </button>
                  <button class="btn btn-outline-danger btn-sm" @click="deleteSupplier(supplier.id)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal para Crear/Editar Proveedor -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="supplierModalLabel">{{ isEditing ? 'Editar' : 'Agregar' }} Proveedor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveSupplier">
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" v-model="currentSupplier.nombre" required>
              </div>
              <div class="mb-3">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" class="form-control" id="contacto" v-model="currentSupplier.contacto">
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" v-model="currentSupplier.telefono">
              </div>
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" v-model="currentSupplier.direccion">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para Re-stock / Compra -->
    <div class="modal fade" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="restockModalLabel">Re-stock desde {{ selectedSupplier?.nombre || 'Proveedor' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitRestock">
              <div class="mb-3">
                <label class="form-label">Proveedor</label>
                <input type="text" class="form-control" :value="selectedSupplier?.nombre" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Productos a Re-stock</label>
                <div v-for="item in restockItems" :key="item.producto_id" class="border p-2 mb-2 rounded">
                  <select v-model="item.producto_id" class="form-select d-inline-block w-auto me-2" style="width: auto;">
                    <option value="">Seleccionar Producto</option>
                    <option v-for="prod in availableProducts" :key="prod.id" :value="prod.id">{{ prod.nombre }}</option>
                  </select>
                  <input v-model.number="item.cantidad" type="number" class="form-control d-inline-block w-auto me-2" placeholder="Cantidad" min="1" style="width: 100px;">
                  <input v-model.number="item.costo_unitario" type="number" step="0.01" class="form-control d-inline-block w-auto" placeholder="Costo Unit." style="width: 120px;">
                  <button type="button" class="btn btn-danger btn-sm ms-2" @click="removeRestockItem(item.producto_id)">Eliminar</button>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" @click="addRestockItem">+ Agregar Producto</button>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" :disabled="restockItems.length === 0">Registrar Compra</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Modal } from 'bootstrap';

const suppliers = ref([]);
const loading = ref(true);
const error = ref(null);
const isEditing = ref(false);
const currentSupplier = ref({});
let supplierModal = null;

// Variables para el modal de re-stock
const selectedSupplier = ref(null);
const restockItems = ref([]);
const availableProducts = ref([]);
let restockModal = null;

const API_URL = 'http://127.0.0.1:8000/api';

const fetchSuppliers = async () => {
  loading.value = true;
  try {
    const response = await axios.get(`${API_URL}/proveedores`);
    suppliers.value = response.data;
  } catch (err) {
    error.value = 'Error al cargar los proveedores.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const fetchAvailableProducts = async () => {
  try {
    const response = await axios.get(`${API_URL}/productos`);
    availableProducts.value = response.data;
  } catch (err) {
    console.error('Error al cargar productos:', err);
  }
};

const openModal = (supplier = null) => {
  if (supplier) {
    isEditing.value = true;
    currentSupplier.value = { ...supplier };
  } else {
    isEditing.value = false;
    currentSupplier.value = {};
  }
  supplierModal.show();
};

const saveSupplier = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`${API_URL}/proveedores/${currentSupplier.value.id}`, currentSupplier.value);
    } else {
      await axios.post(`${API_URL}/proveedores`, currentSupplier.value);
    }
    fetchSuppliers();
    supplierModal.hide();
  } catch (err) {
    console.error('Error al guardar el proveedor:', err);
    alert('No se pudo guardar el proveedor.');
  }
};

const deleteSupplier = async (id) => {
  if (confirm('¿Estás seguro de que quieres eliminar este proveedor?')) {
    try {
      await axios.delete(`${API_URL}/proveedores/${id}`);
      fetchSuppliers();
    } catch (err) {
      console.error('Error al eliminar el proveedor:', err);
      alert('No se pudo eliminar el proveedor.');
    }
  }
};

// Funciones para el modal de re-stock
const openRestockModal = (supplier) => {
  selectedSupplier.value = supplier;
  restockItems.value = [];
  restockModal.show();
};

const addRestockItem = () => {
  restockItems.value.push({ producto_id: '', cantidad: 1, costo_unitario: 0 });
};

const removeRestockItem = (producto_id) => {
  restockItems.value = restockItems.value.filter(item => item.producto_id !== producto_id);
};

const submitRestock = async () => {
  if (restockItems.value.length === 0) {
    alert('Agregue al menos un producto para re-stock.');
    return;
  }
  try {
    const payload = {
      proveedor_id: selectedSupplier.value.id,
      items: restockItems.value
    };
    await axios.post(`${API_URL}/compras`, payload);
    alert('Compra registrada exitosamente.');
    restockModal.hide();
  } catch (err) {
    console.error('Error al registrar la compra:', err);
    alert('No se pudo registrar la compra.');
  }
};

onMounted(() => {
  fetchSuppliers();
  fetchAvailableProducts();
  supplierModal = new Modal(document.getElementById('supplierModal'));
  restockModal = new Modal(document.getElementById('restockModal'));
});
</script>
