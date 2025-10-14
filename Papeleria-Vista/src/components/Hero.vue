<template>
  <div class="container my-5">
    <div class="row g-4">
      <div class="col-lg-7">
        <div
          class="card bg-dark text-white border-0 h-100 position-relative overflow-hidden"
          :style="{ backgroundImage: `url(${marcadores})` }"
        >
          <div class="hero-card-overlay"></div>
          <div class="card-body hero-card-content">
            <h1 class="display-4 fw-bold">Marcadores para colorear</h1>
            <a href="#" class="btn btn-light btn-lg mt-3">Ver más</a>
          </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="d-flex flex-column gap-4 h-100">
          <div
            class="card bg-dark text-white border-0 flex-grow-1 position-relative overflow-hidden"
            :style="{ backgroundImage: `url(${cuadernos})` }"
          >
            <div class="hero-card-overlay"></div>
            <div class="card-body hero-card-content small">
              <h3 class="fw-bold">Medida de Cuadernos</h3>
              <a href="#" class="btn btn-light mt-2">Ver más</a>
            </div>
          </div>

          <div
            class="card bg-dark text-white border-0 flex-grow-1 position-relative overflow-hidden"
            :style="{ backgroundImage: `url(${audifonos})` }"
          >
            <div class="hero-card-overlay"></div>
            <div class="card-body hero-card-content small">
              <h3 class="fw-bold">Audífonos GRATIS</h3>
              <a href="#" class="btn btn-light mt-2">Ver más</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// 🚨 CORRECCIÓN: Importar las imágenes para que Vite procese la ruta
import marcadores from '@/assets/img/marcadores.webp';
import cuadernos from '@/assets/img/cuadernos.png';
import audifonos from '@/assets/img/audifonos.png';
// 🚨 NOTA: Asegúrate de que estas rutas son correctas desde la carpeta src/

</script>

<style scoped>
/* Estilos existentes */
.hero-card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  z-index: 1;
}

.hero-card-content {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  height: 100%;
  padding: 2rem;
}

.hero-card-content.small {
  padding: 1.5rem;
}

/* ================================================= */
/* 📏 AJUSTE DE TAMAÑO (Altura) */
/* ================================================= */

/* Altura mínima para la tarjeta principal (izquierda) */
.col-lg-7 .card {
  /* Establece una altura mínima para que ocupe más espacio */
  min-height: 450px; 
}

/* Ajusta la altura de las tarjetas pequeñas (derecha) */
.col-lg-5 .card {
  /* La columna derecha se divide en dos, por lo que las ajustamos para que sean visualmente grandes */
  min-height: 215px; 
}

/* ================================================= */
/* 🚀 EFECTOS HOVER */
/* ================================================= */

.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease; 
  cursor: pointer; 
  transform-style: preserve-3d; 
}

.card:hover {
  transform: translateY(-5px); 
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4); 
}
</style>