import { createRouter, createWebHistory } from 'vue-router';
import MenuView from '../pages/MenuView.vue';

// Lazy-loaded pages for future sections
const OrdersView = () => import('../pages/OrdersView.vue');

const routes = [
    {
        path: '/:restaurant_slug/:table_uuid',
        name: 'menu',
        component: MenuView,
        meta: { title: 'Menu' },
    },
    {
        path: '/:restaurant_slug/:table_uuid/orders',
        name: 'orders',
        component: OrdersView,
        meta: { title: 'My Orders' },
    },
    {
        // Catch-all fallback
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: {
            template: `
                <div class="min-h-screen flex flex-col items-center justify-center gap-4 bg-white p-8">
                    <span class="text-6xl">🍽️</span>
                    <h1 class="font-bold text-xl text-gray-800">Table not found</h1>
                    <p class="text-sm text-gray-400 text-center">Please scan the QR code at your table to access the menu.</p>
                </div>
            `,
        },
    },
];

const router = createRouter({
    history: createWebHistory('/app'),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition;
        return { top: 0 };
    },
});

router.afterEach((to) => {
    if (to.meta?.title) {
        document.title = `${to.meta.title} - Dine`;
    }
});

export default router;