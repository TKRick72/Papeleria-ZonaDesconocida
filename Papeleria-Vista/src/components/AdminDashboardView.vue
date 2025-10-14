<template>
  <div class="container my-5">
    <h2 class="mb-4 text-primary">📊 Dashboard de Administración</h2>
    <p class="text-muted">Métricas clave de ERP, SCM y CRM de tu papelería.</p>
    <hr>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando métricas...</span>
      </div>
      <p class="mt-2">Cargando datos del servidor...</p>
    </div>

    <div v-else-if="error" class="alert alert-danger">
      Hubo un error al cargar los datos del dashboard. Por favor, verifica tu conexión API.
    </div>

    <div v-else class="row g-4">
      
      <div class="col-lg-3 col-md-6">
        <MetricCard 
          title="Ventas Históricas" 
          :value="formatCurrency(metrics.resumen_ventas.total_ventas_historial)" 
          icon="bi-cash-stack" 
          variant="primary" 
        />
      </div>
      <div class="col-lg-3 col-md-6">
        <MetricCard 
          title="Pedidos Pendientes" 
          :value="metrics.resumen_ventas.pedidos_pendientes" 
          icon="bi-truck" 
          variant="warning" 
        />
      </div>
      <div class="col-lg-3 col-md-6">
        <MetricCard 
          title="Clientes Registrados" 
          :value="metrics.resumen_clientes.total_clientes" 
          icon="bi-people" 
          variant="info" 
        />
      </div>
      <div class="col-lg-3 col-md-6">
        <MetricCard 
          title="Productos Bajo Stock" 
          :value="metrics.resumen_inventario.productos_bajo_stock" 
          icon="bi-box-seam" 
          variant="danger" 
        />
      </div>

      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-light">📈 Ventas por Mes (Simulación Gráfica)</div>
          <div class="card-body">
            <SalesChart /> 
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-light">💰 Análisis SCM/Rentabilidad</div>
          <div class="card-body">
            <h5 class="card-title">Proveedor Más Rentable (SCM)</h5>
            <p v-if="metrics.mejor_proveedor">
                El proveedor **{{ metrics.mejor_proveedor.proveedor_nombre }}** ha generado la mayor ganancia neta: 
                <span class="text-success fw-bold">{{ formatCurrency(metrics.mejor_proveedor.ganancia_neta) }}</span>.
            </p>
            <p v-else>No hay suficientes datos de ventas para calcular la rentabilidad.</p>
            <hr>
            <h5 class="card-title">Top 5 Productos Vendidos (ERP)</h5>
            <ol>
                <li v-for="item in metrics.top_5_vendidos" :key="item.producto_id">
                    Producto ID {{ item.producto_id }}: {{ item.total_vendido }} unidades
                </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
// 🚨 Nota: Necesitas crear estos componentes básicos:
import MetricCard from '../components/MetricCard.vue'; 
import SalesChart from '../components/SalesChart.vue'; 

const metrics = ref(null);
const loading = ref(true);
const error = ref(null);

const API_URL = 'http://127.0.0.1:8000/api';

const fetchMetrics = async () => {
    loading.value = true;
    error.value = null;
    try {
        // Llamada al endpoint protegido de Laravel
        const response = await axios.get(`${API_URL}/dashboard`);
        metrics.value = response.data;
        console.log('Métricas cargadas:', response.data);
    } catch (err) {
        error.value = err.response?.data?.message || 'Error de conexión con el backend.';
        console.error("Error fetching dashboard metrics:", err);
        // Si hay un 403 o 401, el middleware lo manejará, pero mostramos el error si es otro fallo.
    } finally {
        loading.value = false;
    }
};

// Función de formato de moneda (utilizada en el template)
const formatCurrency = (value) => {
    // Aseguramos que el valor sea un número antes de formatear
    const num = parseFloat(value);
    if (isNaN(num)) return '$0.00';
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(num);
};

onMounted(fetchMetrics);
</script>