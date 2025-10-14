import { createRouter, createWebHistory } from 'vue-router';

// 1. Importar las Vistas/Componentes de Página
// 🚨 CORRECCIÓN CLAVE: La ruta a HomeView/App se mantiene
import HomeView from '../views/HomeView.vue'; 
// 🚨 CORRECCIÓN: Ajustamos la ruta para que apunte a la carpeta 'components'
import CheckoutView from '../components/CheckoutView.vue'; 
import AdminDashboardView from '../components/AdminDashboardView.vue';
import InventoryView from '../components/InventoryView.vue';
import SuppliersView from '../views/SuppliersView.vue';
import ClientsView from '../views/ClientsView.vue';

// 2. Definir el array de rutas
const routes = [
    {
        path: '/',
        name: 'Home',
        component: HomeView // La página principal
    },
    {
        path: '/checkout',
        name: 'Checkout',
        component: CheckoutView,
        // Protección de Ruta: Solo usuarios logueados pueden acceder al checkout
        meta: { requiresAuth: true } 
    },
    {
        path: '/admin',
        name: 'Admin',
        component: AdminDashboardView,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/inventory',
        name: 'Inventory',
        component: InventoryView,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/suppliers',
        name: 'Suppliers',
        component: SuppliersView,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/clients',
        name: 'Clients',
        component: ClientsView,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
];

// 3. Crear la instancia del router
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

// 4. Guardia de Navegación (Protege rutas con meta: { requiresAuth: true })
router.beforeEach((to, from, next) => {
    // Importa el estado de autenticación (auth) desde tu store
    const auth = window.auth;

    if (to.meta.requiresAuth && !auth.isLoggedIn) {
        alert('Debes iniciar sesión para acceder a esta sección.');
        return next('/');
    }

    if (to.meta.requiresAdmin && auth.user?.rol !== 'administrador') {
        alert('No tienes permisos de administrador para acceder aquí.');
        return next('/');
    }

    next();
});


// 5. Exportar para usar en main.js
export default router;