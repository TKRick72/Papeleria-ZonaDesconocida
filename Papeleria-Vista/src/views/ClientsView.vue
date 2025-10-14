<template>
  <div class="container my-5">
    <h2 class="mb-4">Gestión de Clientes (CRM)</h2>
    <p class="text-muted">Administra clientes, pedidos y envíos.</p>

    <!-- Tabs para navegación interna -->
    <ul class="nav nav-tabs mb-4" id="clientsTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="clients-tab" data-bs-toggle="tab" data-bs-target="#clients" type="button" role="tab">Clientes</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Pedidos</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="shipments-tab" data-bs-toggle="tab" data-bs-target="#shipments" type="button" role="tab">Envíos</button>
      </li>
    </ul>

    <div class="tab-content" id="clientsTabsContent">
      <!-- Tab Clientes: Listar y eliminar -->
      <div class="tab-pane fade show active" id="clients" role="tabpanel">
        <div class="card shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Clientes</h5>
          </div>
          <div class="card-body">
            <div v-if="clientsLoading" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando clientes...</span>
              </div>
            </div>
            <div v-else-if="clientsError" class="alert alert-danger">
              {{ clientsError }}
            </div>
            <div v-else class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="client in clients" :key="client.id">
                    <td>{{ client.id }}</td>
                    <td>{{ client.nombre }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.telefono || 'N/A' }}</td>
                    <td>
                      <button class="btn btn-outline-danger btn-sm" @click="deleteClient(client.id)">
                        <i class="bi bi-trash"></i> Eliminar
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <p v-if="clients.length === 0" class="text-muted">No hay clientes registrados.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Pedidos: Listar todos los pedidos -->
      <div class="tab-pane fade" id="orders" role="tabpanel">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0">Todos los Pedidos</h5>
          </div>
          <div class="card-body">
            <div v-if="ordersLoading" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando pedidos...</span>
              </div>
            </div>
            <div v-else-if="ordersError" class="alert alert-danger">
              {{ ordersError }}
            </div>
            <div v-else class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="order in orders" :key="order.id">
                    <td>{{ order.id }}</td>
                    <td>{{ order.usuario?.nombre || 'N/A' }}</td>
                    <td>${{ order.total }}</td>
                    <td><span class="badge bg-secondary">{{ order.estado }}</span></td>
                    <td>{{ new Date(order.created_at).toLocaleDateString() }}</td>
                  </tr>
                </tbody>
              </table>
              <p v-if="orders.length === 0" class="text-muted">No hay pedidos.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Envíos: Listar y actualizar estado -->
      <div class="tab-pane fade" id="shipments" role="tabpanel">
        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0">Gestión de Envíos</h5>
          </div>
          <div class="card-body">
            <div v-if="shipmentsLoading" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando envíos...</span>
              </div>
            </div>
            <div v-else-if="shipmentsError" class="alert alert-danger">
              {{ shipmentsError }}
            </div>
            <div v-else class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>ID Pedido</th>
                    <th>Dirección</th>
                    <th>Estado Actual</th>
                    <th>Nuevo Estado</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="shipment in shipments" :key="shipment.id">
                    <td>{{ shipment.pedido_id }}</td>
                    <td>{{ shipment.direccion_envio }}</td>
                    <td><span class="badge bg-info">{{ shipment.estado_envio }}</span></td>
                    <td>
                      <select v-model="shipment.newStatus" class="form-select form-select-sm">
                        <option value="preparacion">Preparación</option>
                        <option value="en_transito">En Tránsito</option>
                        <option value="entregado">Entregado</option>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-outline-primary btn-sm" @click="updateShipment(shipment.id, shipment.newStatus)" :disabled="shipment.newStatus === shipment.estado_envio">
                        Actualizar
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <p v-if="shipments.length === 0" class="text-muted">No hay envíos.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const clients = ref([]);
const orders = ref([]);
const shipments = ref([]);
const clientsLoading = ref(true);
const ordersLoading = ref(true);
const shipmentsLoading = ref(true);
const clientsError = ref(null);
const ordersError = ref(null);
const shipmentsError = ref(null);

const API_URL = 'http://127.0.0.1:8000/api';

const fetchClients = async () => {
  clientsLoading.value = true;
  clientsError.value = null;
  try {
    const response = await axios.get(`${API_URL}/clientes`);
    clients.value = response.data;
  } catch (err) {
    clientsError.value = 'Error al cargar clientes.';
    console.error(err);
  } finally {
    clientsLoading.value = false;
  }
};

const deleteClient = async (id) => {
  if (confirm('¿Estás seguro de eliminar este cliente?')) {
    try {
      await axios.delete(`${API_URL}/clientes/${id}`);
      fetchClients();
    } catch (err) {
      alert('Error al eliminar el cliente.');
      console.error(err);
    }
  }
};

const fetchOrders = async () => {
  ordersLoading.value = true;
  ordersError.value = null;
  try {
    const response = await axios.get(`${API_URL}/pedidos`);
    orders.value = response.data;
  } catch (err) {
    ordersError.value = 'Error al cargar pedidos.';
    console.error(err);
  } finally {
    ordersLoading.value = false;
  }
};

const fetchShipments = async () => {
  shipmentsLoading.value = true;
  shipmentsError.value = null;
  try {
    // Fetch pedidos with envios relation (assume backend loads it)
    const response = await axios.get(`${API_URL}/pedidos`);
    shipments.value = response.data.map(pedido => pedido.envio).filter(envio => envio);
    // Initialize newStatus for each
    shipments.value.forEach(shipment => {
      shipment.newStatus = shipment.estado_envio;
    });
  } catch (err) {
    shipmentsError.value = 'Error al cargar envíos.';
    console.error(err);
  } finally {
    shipmentsLoading.value = false;
  }
};

const updateShipment = async (id, newStatus) => {
  if (newStatus === 'preparacion' || newStatus === 'en_transito' || newStatus === 'entregado') {
    try {
      await axios.put(`${API_URL}/envios/${id}`, { estado_envio: newStatus });
      alert('Estado del envío actualizado.');
      fetchShipments(); // Refresh
    } catch (err) {
      alert('Error al actualizar el envío.');
      console.error(err);
    }
  }
};

onMounted(() => {
  fetchClients();
  fetchOrders();
  fetchShipments();
});
</script>
