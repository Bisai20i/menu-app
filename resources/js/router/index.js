import { createRouter, createWebHistory } from 'vue-router';
import CartView from '../pages/CartView.vue';
import OrdersView from '../pages/OrdersView.vue';
import MenuView from '../pages/MenuView.vue';
// import Home from '../pages/Home.vue';
// import Cart from '../pages/Cart.vue';

// const routes = [
//     { path: '/', component: Home },
//     { path: '/cart', component: Cart },
// ];

const routes = [
    { path: '/', component: MenuView },
    { path: '/cart', component: CartView },
    { path: '/orders', component: OrdersView },
]

const router = createRouter({
    history: createWebHistory('/app'),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition
        return { top: 0 }
    }
});

export default router;