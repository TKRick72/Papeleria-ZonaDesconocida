<template>
  <div class="checkout-page container my-5">
    <h2 class="mb-4">Mi Carrito</h2>
    
    <div class="progress mb-4" style="height: 5px;">
        <div class="progress-bar" role="progressbar" :style="{width: progressWidth}" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="row g-4">
      <div class="col-lg-3">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Datos de Contacto</h5>
          </div>
          <div class="card-body">
            <p><strong>Email:</strong> {{ auth.user?.email || 'N/A' }}</p>
            <p><strong>Nombre:</strong> {{ auth.user?.nombre || 'N/A' }}</p>
            <p class="text-muted small">Teléfono: (229) 109 5158 (Simulado)</p>
            <button class="btn btn-outline-primary w-100" :disabled="!auth.user">Editar</button>
          </div>
        </div>
      </div>
      
      <div class="col-lg-5">
        
        <ShippingForm 
            v-if="currentStep === 1" 
            @shipping-complete="goToPayment" 
        />
        
        <PaymentForm 
            v-if="currentStep === 2" 
            @payment-complete="finalizeOrder" 
        />
        
        <div v-if="currentStep === 2 && shippingData" class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Envío Seleccionado</h5>
            </div>
            <div class="card-body">
                <p class="mb-1">{{ shippingData.street }} #{{ shippingData.numberExt }}</p>
                <p class="mb-1">{{ shippingData.colony }} - {{ shippingData.zip }}, Misantla, Veracruz</p>
                <p class="text-muted small">Destinatario: {{ shippingData.recipient }}</p>
                <button class="btn btn-outline-primary btn-sm w-100" @click="currentStep = 1">Cambiar Envío</button>
            </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <CheckoutSummary />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { auth, cart, logout } from '../store.js';
import ShippingForm from '../components/ShippingForm.vue';
import PaymentForm from '../components/PaymentForm.vue';
import CheckoutSummary from '../components/CheckoutSummary.vue';
import axios from 'axios';

const currentStep = ref(1);
const shippingData = ref(null);
const paymentData = ref(null);
const router = useRouter();

const API_URL = 'http://127.0.0.1:8000/api';

// Controla el avance de la barra
const progressWidth = computed(() => {
    if (currentStep.value === 1) return '33%';
    if (currentStep.value === 2) return '66%';
    return '100%';
});

// Computado para el total del carrito
const total = computed(() => {
    return cart.items.reduce((sum, item) => sum + (parseFloat(item.precio) * item.quantity), 0);
});

// Función para pasar al paso de pago
const goToPayment = (data) => {
    shippingData.value = data;
    currentStep.value = 2;
};

const finalizeOrder = async (method) => {
    paymentData.value = method;
    
    if (cart.items.length === 0) {
        alert('Tu carrito está vacío. Añade productos para finalizar la compra.');
        return;
    }

    if (!shippingData.value) {
        alert('Por favor, completa la información de envío.');
        currentStep.value = 1;
        return;
    }

    if (!auth.isLoggedIn) {
        alert('Debes iniciar sesión para completar la compra.');
        router.push('/');
        return;
    }

    try {
        const payload = {
            // Datos del Pedido
            total: total.value, 
            items: cart.items.map(item => ({
                producto_id: item.id,
                cantidad: item.quantity
            })),
            
            // Datos del Pago
            metodo_pago: method,
            
            // Datos del Envío
            direccion_envio: `${shippingData.value.street} #${shippingData.value.numberExt}, Col. ${shippingData.value.colony}, C.P. ${shippingData.value.zip}`,
            // Omite destinatario ya que el backend no lo usa directamente
        };

        // 🚨 LLAMADA CRÍTICA AL BACKEND
        const response = await axios.post(`${API_URL}/pedidos`, payload, {
            headers: {
                'Authorization': `Bearer ${auth.token}`,
                'Content-Type': 'application/json'
            }
        });

        // Éxito: Mostrar mensaje de envío y limpiar carrito
        alert(`¡Felicidades, tu compra fue enviada! ID de Pedido: ${response.data.id || 'N/A'}. Se enviará a ${shippingData.value.street}.`);
        
        // Limpiar carrito después de la compra
        cart.items = [];
        localStorage.removeItem('cart_items');

        // Redirigir a la página principal
        router.push('/');

    } catch (error) {
        console.error("Error al finalizar la compra:", error.response || error);
        const errorMessage = error.response?.data?.message || error.response?.data || 'Error desconocido en el servidor (500). Verifica que el backend esté configurado correctamente para /api/pedidos.';
        alert(`Error al procesar la compra: ${errorMessage}`);
    }
};

onMounted(() => {
    // Si el usuario no está logueado y hay ítems, redirigir a login
    if (!auth.isLoggedIn && cart.items.length > 0) {
        alert('Debes iniciar sesión para continuar con la compra.');
        router.push('/');
        return;
    }

    // Si el carrito está vacío, redirigir a la página principal
    if (cart.items.length === 0) {
        alert('Tu carrito está vacío. Serás redirigido al catálogo.');
        router.push('/');
    }
});
</script>

<style scoped>
.checkout-page {
    min-height: 80vh;
}
</style>