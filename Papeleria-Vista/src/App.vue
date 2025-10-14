<template>
  <div>
    <Navbar @toggle-cart="toggleCart" @open-login-modal="isModalVisible = true" />
    
    <LoginModal :is-visible="isModalVisible" @close="isModalVisible = false" />
    
    <ShoppingCart :is-visible="isCartVisible" @toggle-cart="toggleCart" />

    <main class="container mt-4">
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from './components/Navbar.vue';
import ShoppingCart from './components/ShoppingCart.vue';
import LoginModal from './components/LoginModal.vue';
import Footer from './components/Footer.vue';

const isCartVisible = ref(false);
const isModalVisible = ref(false);

function toggleCart() {
  isCartVisible.value = !isCartVisible.value;
}
</script>