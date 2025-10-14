<template>
  <main class="container mt-4">
    <Hero />
    <Categories />
    <ProductList />
    <ShippingInfo />
    <CompanyInfo />
    <Newsletter />
  </main>
</template>

<script setup>
// Asegúrate de que todas las importaciones necesarias (Hero, Categories, etc.)
// estén aquí en HomeView.vue, no en App.vue.

import Hero from '../components/Hero.vue';
import Categories from '../components/Categories.vue';
import ProductList from '../components/ProductList.vue';
import CompanyInfo from '../components/CompanyInfo.vue';
import ShippingInfo from '../components/ShippingInfo.vue';
import Newsletter from '../components/Newsletter.vue';
</script>