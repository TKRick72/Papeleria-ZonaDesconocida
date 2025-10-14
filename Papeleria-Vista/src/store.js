import { reactive, computed, watch } from 'vue';
import axios from 'axios';

// ====================================================
// CONFIGURACIÓN INICIAL
// ====================================================

// URL base de tu API de Laravel
const API_URL = 'http://127.0.0.1:8000/api';

// ----------------------------------------------------
// I. LÓGICA DE AUTENTICACIÓN (AUTH)
// ----------------------------------------------------

// 1. Estado inicial de autenticación
const authState = {
    token: localStorage.getItem('auth_token') || null,
    user: null, 
};

// Intenta cargar el user de forma segura después de definir authState
const storedUser = localStorage.getItem('auth_user');

if (storedUser && storedUser !== 'undefined') {
    try {
        authState.user = JSON.parse(storedUser);
    } catch (e) {
        console.error("Error al parsear auth_user desde localStorage:", e);
        localStorage.removeItem('auth_user');
    }
}

// 2. Estado reactivo principal
export const auth = reactive(authState);

// 3. Propiedad Computada: Saber si el usuario está logueado
export const isLoggedIn = computed(() => auth.token && auth.user); 
auth.isLoggedIn = isLoggedIn; // Añadir al objeto auth para fácil acceso

/**
 * Función para iniciar sesión (Action)
 */
export async function login(email, password) {
    try {
        const response = await axios.post(`${API_URL}/login`, { email, password });
        
        // Desestructuración simple, asumiendo que el backend devuelve {usuario: user, token: token}
        const { usuario: user, token } = response.data; 

        // Guardar datos en el estado reactivo
        auth.token = token;
        auth.user = user;

        // Guardar datos en localStorage
        localStorage.setItem('auth_token', token);
        localStorage.setItem('auth_user', JSON.stringify(user));

        // Establecer el token para futuras peticiones
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        return true; 
    } catch (error) {
        console.error("Error de login:", error.response?.data?.message || error.message);
        return false; 
    }
}

/**
 * Función para cerrar sesión (Action)
 */
export async function logout() {
    try {
        await axios.post(`${API_URL}/logout`);
    } catch (error) {
        console.warn("Logout falló o token expiró. Limpiando estado local.");
    } finally {
        // Siempre limpiar el estado localmente
        auth.token = null;
        auth.user = null;
        
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        localStorage.removeItem('cart_items'); // Limpiar carrito al cerrar sesión
        
        delete axios.defaults.headers.common['Authorization'];
    }
}

// Inicialización: Configurar Axios si ya existe un token al cargar la página
if (auth.token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`;
}


// ----------------------------------------------------
// II. LÓGICA DEL CARRITO (Con soporte para cantidad)
// ----------------------------------------------------

const cartState = {
    items: JSON.parse(localStorage.getItem('cart_items')) || [],
};

export const cart = reactive(cartState);

/**
 * Función para añadir un producto al carrito.
 * @param {Object} product - Objeto producto de la API.
 * @param {number} quantity - Cantidad a añadir.
 */
export function addToCart(product, quantity = 1) {
    const existingItem = cart.items.find(item => item.id === product.id);
    
    // Si existe, incrementa la cantidad (sumando la nueva cantidad)
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        // Si es nuevo, lo agrega con el precio de venta y la cantidad especificada
        // Usamos los campos de la API (nombre, precio)
        cart.items.push({ 
            id: product.id, 
            nombre: product.nombre, 
            precio: product.precio, 
            quantity: quantity 
        });
    }
    // El 'watch' al final del archivo se encarga de guardarlo en localStorage
}

/**
 * Función para eliminar un producto o reducir la cantidad.
 * @param {number} productId - ID del producto a eliminar.
 */
export function removeFromCart(productId) {
    // Para simplificar, esta función elimina el item completo
    const index = cart.items.findIndex(item => item.id === productId);
    if (index !== -1) {
        cart.items.splice(index, 1);
    }
}

// Observador que guarda el carrito en localStorage cada vez que cambia
watch(
    () => cart.items,
    (newItems) => {
        localStorage.setItem('cart_items', JSON.stringify(newItems));
    },
    { deep: true } 
);