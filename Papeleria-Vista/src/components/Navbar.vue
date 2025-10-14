<template>
  <nav class="navbar navbar-dark bg-dark px-4 d-flex justify-content-between align-items-center">
    <a class="navbar-brand fw-bold" href="#">
      Zona Desconocida
    </a>

    <div class="d-flex align-items-center">

      <form class="d-flex me-3">
        <div class="input-group">
          <input class="form-control" type="search" placeholder="Buscar productos...">
          <button class="btn btn-outline-light" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>

      <div class="me-3">
        <template v-if="isLoggedIn">
          <BDropdown variant="outline-light" text="white" class="text-white">
            <template #button-content>
              <i class="bi bi-person-circle me-2"></i>
              {{ auth.user?.nombre }}
            </template>
            <BDropdownItem @click="openEditProfile">
              Mi Perfil
            </BDropdownItem>

            <template v-if="isAdmin">
              <BDropdownDivider />
              <BDropdownHeader class="text-warning">ADMINISTRACIÓN (ERP/SCM)</BDropdownHeader>

              <BDropdownItem @click="goToAdminDashboard">
                📊 Dashboard & Métricas
              </BDropdownItem>
              <BDropdownItem @click="goToInventory">
                📦 Gestión de Inventario
              </BDropdownItem>
              <BDropdownItem @click="goToSuppliers">
                🚚 Proveedores & Costos
              </BDropdownItem>
              <BDropdownItem @click="goToClients">
                👥 Gestión de Clientes
              </BDropdownItem>
            </template>
            <BDropdownDivider />
            <BDropdownItem @click="handleLogout">
              Cerrar Sesión
            </BDropdownItem>
          </BDropdown>
        </template>

        <template v-else>
          <button 
            @click="$emit('open-login-modal')"
            class="btn btn-outline-light text-white"
          >
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Iniciar Sesión
          </button>
        </template>
      </div>

      <button class="btn btn-primary" @click="$emit('toggle-cart')">
        🛒 Carrito
      </button>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { auth, logout } from '../store.js';
import { BDropdown, BDropdownItem, BDropdownDivider, BDropdownHeader } from 'bootstrap-vue-next';
// Asumo que tienes bootstrap-icons importado para los iconos bi-

// Emits necesarios
defineEmits(['toggle-cart', 'open-login-modal']);

const router = useRouter();

// 🚨 PROPIEDAD COMPUTADA CLAVE: Determina si mostrar el menú de Admin
const isAdmin = computed(() => auth.user?.rol === 'administrador');
const isLoggedIn = computed(() => auth.isLoggedIn);


// 🚨 FUNCIONES DE NAVEGACIÓN (Simuladas con alert)
const goToAdminDashboard = () => {
    router.push('/admin');
};
const goToInventory = () => {
    router.push('/inventory');
};
const goToSuppliers = () => {
    router.push('/suppliers');
};
const goToClients = () => {
    router.push('/clients');
};
const openEditProfile = () => {
    // Aquí iría: router.push('/mi-perfil')
    alert("Función de editar perfil próximamente 😄");
}

const handleLogout = async () => {
  try {
    await logout()
    console.log("✅ Sesión cerrada correctamente.")
  } catch (error) {
    console.error("Error al cerrar sesión:", error)
  }
}
</script>

<style scoped>
.navbar-brand {
  font-size: 1.5rem;
}
.btn {
    cursor: pointer; 
}
</style>