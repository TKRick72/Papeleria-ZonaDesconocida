<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">Explora nuestras Categorías</h2>
    
    <div v-if="loading" class="text-center text-primary">
        <span class="spinner-border spinner-border-sm me-2"></span>
        Cargando categorías...
    </div>

    <div v-else-if="error" class="alert alert-danger text-center">
        Error al cargar categorías: {{ error }}
    </div>

    <div v-else class="row g-4 justify-content-center">
      
      <div 
        v-for="cat in categorias" 
        :key="cat.id" 
        class="col-12 col-sm-6 col-md-6 col-lg-4" 
        style="max-width: 350px;" 
      >
        <div 
          class="card category-card p-4 text-center h-100" 
          @click="handleCategoryClick(cat.id, cat.nombre)"
        >
          <div class="card-body">
            <i :class="getIconClass(cat.nombre)" class="icon-xlarge mb-3"></i>
            
            <h5 class="card-title fw-bold">{{ cat.nombre }}</h5>
            
            <p class="card-text text-muted small mt-2">{{ getDescription(cat.nombre) }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Estado
const categorias = ref([]);
const loading = ref(true);
const error = ref(null);

// Endpoint de tu API
const API_URL = 'http://127.0.0.1:8000/api';

// Función para cargar las categorías desde Laravel
const fetchCategories = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get(`${API_URL}/categorias`);
        categorias.value = response.data;
    } catch (err) {
        error.value = 'No se pudieron cargar las categorías del servidor.';
        console.error("Error fetching categories:", err);
    } finally {
        loading.value = false;
    }
};

// Función que se ejecuta al hacer click
const handleCategoryClick = (categoryId, categoryName) => {
    alert(`Pronto implementaremos el filtro para ${categoryName}! (ID: ${categoryId}).`);
};


// Lógica para asignar iconos según el nombre
const getIconClass = (nombre) => {
    const name = nombre.toLowerCase();
    if (name.includes('oficina')) return 'bi bi-briefcase';
    if (name.includes('papelería') || name.includes('papeleria')) return 'bi bi-pencil-square';
    if (name.includes('electrónica') || name.includes('electronica')) return 'bi bi-laptop';
    if (name.includes('escritura')) return 'bi bi-pen';
    if (name.includes('arte') || name.includes('dibujo')) return 'bi bi-palette';
    return 'bi bi-tags';
};

// Lógica para asignar descripciones
const getDescription = (nombre) => {
    const name = nombre.toLowerCase();
    if (name.includes('oficina')) return 'Todo para equipar tu espacio de trabajo y mantener el orden.';
    if (name.includes('papelería') || name.includes('papeleria')) return 'Cuadernos, papel, y todo lo básico para la escuela y el hogar.';
    if (name.includes('electrónica') || name.includes('electronica')) return 'Gadgets, calculadoras y accesorios tecnológicos.';
    if (name.includes('escritura')) return 'Plumas, lápices y marcadores de la mejor calidad.';
    if (name.includes('arte') || name.includes('dibujo')) return 'Pinturas, lienzos y sets creativos para artistas.';
    return 'Artículos diversos para todas tus necesidades.';
};


onMounted(fetchCategories);
</script>

<style scoped>
/* Estilos existentes */
.category-card {
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid #dee2e6;
  /* El p-4 del template ya ayuda, pero ajustamos el padding para aire */
  padding: 1.5rem !important; 
}
.category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  background-color: #f8f9fa;
}

/* 🚨 CORRECCIÓN 3: Tamaño grande para el ícono */
.icon-xlarge {
    font-size: 3.5rem; 
    color: var(--bs-primary);
}

.card-title {
    font-size: 1.25rem; 
}
</style>