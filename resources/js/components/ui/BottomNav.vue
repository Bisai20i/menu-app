<template>
    <nav class="fixed bottom-0 left-0 right-0 z-20 bg-white border-t border-gray-100 shadow-lg"
        style="padding-bottom: env(safe-area-inset-bottom);">
        <div class="max-w-screen-xl mx-auto flex">
            <!-- Menu tab -->
            <RouterLink
                :to="{ name: 'menu', params: { restaurant_slug: route.params.restaurant_slug, table_uuid: route.params.table_uuid } }"
                class="flex-1 flex flex-col items-center gap-1 py-3 transition-colors"
                :class="isMenu ? 'text-orange-500' : 'text-gray-400 hover:text-gray-600'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" />
                    <path d="M9 12h6M9 16h4" />
                </svg>
                <span class="text-[10px] font-bold tracking-wide">Menu</span>
                <div class="h-0.5 w-6 rounded-full transition-all"
                    :class="isMenu ? 'bg-orange-500' : 'bg-transparent'" />
            </RouterLink>

            <!-- Orders tab -->
            <RouterLink
                :to="{ name: 'orders', params: { restaurant_slug: route.params.restaurant_slug, table_uuid: route.params.table_uuid } }"
                class="flex-1 flex flex-col items-center gap-1 py-3 transition-colors relative"
                :class="isOrders ? 'text-orange-500' : 'text-gray-400 hover:text-gray-600'">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <!-- Pending orders dot -->
                    <span v-if="hasPendingOrders"
                        class="absolute -top-1 -right-1.5 w-2.5 h-2.5 bg-orange-500 rounded-full border-2 border-white" />
                </div>
                <span class="text-[10px] font-bold tracking-wide">My Orders</span>
                <div class="h-0.5 w-6 rounded-full transition-all"
                    :class="isOrders ? 'bg-orange-500' : 'bg-transparent'" />
            </RouterLink>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { useCartStore } from '../../stores/cart.js';

const route = useRoute();
const cartStore = useCartStore();

const isMenu = computed(() => route.name === 'menu');
const isOrders = computed(() => route.name === 'orders');

// Show notification dot if there's an active session (orders exist)
const hasPendingOrders = computed(() => !!cartStore.sessionUuid && !isOrders.value);
</script>