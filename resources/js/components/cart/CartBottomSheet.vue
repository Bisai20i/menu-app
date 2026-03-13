<template>
    <Teleport to="body">
        <!-- Backdrop -->
        <Transition name="fade">
            <div v-if="cartStore.isCartOpen" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40"
                @click="onBackdropClick" />
        </Transition>

        <!-- Bottom Sheet -->
        <Transition name="sheet">
            <div v-if="cartStore.isCartOpen"
                class="fixed bottom-0 left-0 right-0 z-50 flex justify-center"
                style="padding-bottom: env(safe-area-inset-bottom);">

                <div class="bg-white rounded-t-3xl shadow-2xl max-h-[90vh] w-full max-w-[998px] flex flex-col">
                    <!-- Handle bar -->
                    <div class="flex justify-center pt-3 pb-1 shrink-0">
                        <div class="w-10 h-1 bg-gray-200 rounded-full" />
                    </div>

                    <!-- ── STEP 1: CART ─────────────────────────────────────── -->
                    <template v-if="step === 'cart'">
                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 shrink-0">
                            <div>
                                <h2 class="font-bold text-gray-900 text-lg">Your Cart</h2>
                                <p v-if="cartStore.tableNumber" class="text-xs text-gray-400">Table {{
                                    cartStore.tableNumber
                                    }}</p>
                            </div>
                            <button
                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors"
                                @click="cartStore.closeCart()">✕</button>
                        </div>

                        <div v-if="!cartStore.items.length"
                            class="flex-1 flex flex-col items-center justify-center gap-3 py-12">
                            <div class="text-6xl">🛒</div>
                            <p class="font-semibold text-gray-700">Your cart is empty</p>
                            <p class="text-sm text-gray-400 text-center max-w-[200px]">Add some delicious items from the
                                menu!</p>
                            <button
                                class="mt-2 bg-orange-500 text-white font-semibold px-6 py-2.5 rounded-full text-sm hover:bg-orange-600 transition-colors"
                                @click="cartStore.closeCart()">Browse Menu</button>
                        </div>

                        <div v-else class="flex-1 overflow-y-auto px-4 py-3 flex flex-col gap-3">
                            <div v-for="item in cartStore.items" :key="item.id"
                                class="flex items-center gap-3 bg-gray-50 rounded-2xl p-3">
                                <div
                                    class="w-14 h-14 rounded-xl overflow-hidden bg-white flex items-center justify-center shrink-0 shadow-sm">
                                    <img v-if="item.image" :src="item.image" :alt="item.name"
                                        class="w-full h-full object-cover" />
                                    <span v-else class="text-2xl">{{ getEmoji(item) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 text-sm leading-tight line-clamp-1">{{
                                        item.name
                                        }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Rs. {{ fmt(item.price) }} each</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button
                                        class="w-7 h-7 rounded-full bg-white border border-gray-200 text-gray-600 font-bold flex items-center justify-center shadow-sm active:scale-90 transition-transform"
                                        @click="cartStore.removeItem(item.id)">−</button>
                                    <span class="font-bold text-gray-800 text-sm w-5 text-center">{{ item.quantity
                                        }}</span>
                                    <button
                                        class="w-7 h-7 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center shadow-sm active:scale-90 transition-transform"
                                        @click="cartStore.addItem(item)">+</button>
                                    <button
                                        class="ml-1 w-7 h-7 rounded-full bg-red-50 text-red-400 flex items-center justify-center active:scale-90 transition-transform"
                                        @click="cartStore.deleteItem(item.id)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-if="cartStore.items.length" class="px-4 pt-3 pb-4 border-t border-gray-100 shrink-0">
                            <div class="flex items-center justify-between mb-3 text-sm">
                                <span class="text-gray-500">{{ cartStore.totalItems }} item{{ cartStore.totalItems !== 1
                                    ?
                                    's' : '' }}</span>
                                <span class="font-bold text-gray-800">Rs. {{ fmt(cartStore.totalPrice) }}</span>
                            </div>
                            <button
                                class="w-full bg-orange-500 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-orange-600 active:scale-[0.98] transition-all shadow-lg shadow-orange-200 text-base"
                                @click="step = 'summary'">
                                Review Order
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    <!-- ── STEP 2: ORDER SUMMARY ────────────────────────────── -->
                    <template v-else-if="step === 'summary'">
                        <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 shrink-0">
                            <button
                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors shrink-0"
                                @click="step = 'cart'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                            </button>
                            <div class="flex-1">
                                <h2 class="font-bold text-gray-900 text-lg">Order Summary</h2>
                                <p v-if="cartStore.tableNumber" class="text-xs text-gray-400">Table {{
                                    cartStore.tableNumber
                                    }}</p>
                            </div>
                            <button
                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors"
                                @click="cartStore.closeCart(); step = 'cart'">✕</button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-4">
                            <div class="bg-gray-50 rounded-2xl overflow-hidden">
                                <div class="px-4 py-2.5 border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Items</p>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <div v-for="item in cartStore.items" :key="item.id"
                                        class="flex items-center justify-between px-4 py-3 gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span
                                                class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs font-bold flex items-center justify-center shrink-0">
                                                {{ item.quantity }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ item.name
                                                    }}
                                                </p>
                                                <p class="text-xs text-gray-400">Rs. {{ fmt(item.price) }} × {{
                                                    item.quantity }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800 shrink-0">Rs. {{ fmt(item.price *
                                            item.quantity) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">
                                    Special Requests <span
                                        class="font-normal normal-case text-gray-300">(optional)</span>
                                </label>
                                <textarea v-model="cartStore.orderNote"
                                    placeholder="e.g. No onions, extra sauce on the side..." rows="3"
                                    class="w-full text-sm border border-gray-200 rounded-2xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent placeholder:text-gray-300 transition-all" />
                            </div>

                            <div class="bg-gray-50 rounded-2xl px-4 py-3 flex flex-col gap-2">
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>Subtotal ({{ cartStore.totalItems }} items)</span>
                                    <span>Rs. {{ fmt(cartStore.totalPrice) }}</span>
                                </div>
                                <div class="h-px bg-gray-200 my-0.5" />
                                <div class="flex items-center justify-between font-bold text-gray-900">
                                    <span>Total</span>
                                    <span class="text-orange-500 text-base">Rs. {{ fmt(cartStore.totalPrice) }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-gray-400 text-center leading-relaxed px-2">
                                By placing your order you confirm the items above. Payment will be collected at the
                                table.
                            </p>
                        </div>

                        <div class="px-4 pt-3 pb-4 border-t border-gray-100 shrink-0">
                            <button :disabled="isPlacingOrder"
                                class="w-full bg-orange-500 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-orange-600 active:scale-[0.98] transition-all disabled:opacity-60 shadow-lg shadow-orange-200 text-base"
                                @click="confirmOrder">
                                <span v-if="isPlacingOrder"
                                    class="w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin" />
                                <span v-else>🛍️</span>
                                {{ isPlacingOrder ? 'Placing Order...' : `Place Order · Rs.
                                ${fmt(cartStore.totalPrice)}` }}
                            </button>
                        </div>
                    </template>

                    <!-- ── STEP 3: SUCCESS ──────────────────────────────────── -->
                    <template v-else-if="step === 'success'">
                        <div class="flex-1 flex flex-col items-center justify-center gap-5 px-6 py-12 text-center">
                            <div
                                class="w-24 h-24 rounded-full bg-green-50 flex items-center justify-center text-5xl animate-bounce-once">
                                🎉
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-900 text-2xl mb-2">Order Placed!</h2>
                                <p class="text-gray-400 text-sm leading-relaxed max-w-[240px] mx-auto">
                                    Your order is in the kitchen.<br>We'll bring it to your table shortly.
                                </p>
                            </div>
                            <div class="flex flex-col gap-2.5 w-full mt-2">
                                <button
                                    class="w-full bg-orange-500 text-white font-bold py-4 rounded-2xl hover:bg-orange-600 transition-colors"
                                    @click="goToOrders">
                                    Track My Orders
                                </button>
                                <button
                                    class="w-full bg-gray-100 text-gray-600 font-semibold py-4 rounded-2xl hover:bg-gray-200 transition-colors"
                                    @click="cartStore.closeCart(); step = 'cart'">
                                    Back to Menu
                                </button>
                            </div>
                        </div>
                    </template>
                </div>


            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useCartStore } from '../../stores/cart.js';
import { menuApi } from '../../services/api.js';
import { useToast } from '../../composables/useToast.js';

const cartStore = useCartStore();
const toast = useToast();
const router = useRouter();
const route = useRoute();

const step = ref('cart'); // 'cart' | 'summary' | 'success'
const isPlacingOrder = ref(false);

function fmt(price) { return Number(price).toFixed(2); }

function getEmoji(item) {
    const name = item.name?.toLowerCase() || '';
    if (name.includes('pizza')) return '🍕';
    if (name.includes('burger')) return '🍔';
    if (name.includes('pasta')) return '🍝';
    if (name.includes('salad')) return '🥗';
    if (name.includes('soup')) return '🍜';
    if (name.includes('chicken')) return '🍗';
    if (name.includes('dessert') || name.includes('cake')) return '🍰';
    if (name.includes('drink') || name.includes('coffee')) return '☕';
    return '🍽️';
}

function onBackdropClick() {
    if (isPlacingOrder.value) return;
    cartStore.closeCart();
    step.value = 'cart';
}

async function confirmOrder() {
    if (!cartStore.items.length || isPlacingOrder.value) return;
    isPlacingOrder.value = true;
    try {
        const response = await menuApi.placeOrder({
            restaurant_id: cartStore.restaurantId,
            table_uuid: cartStore.tableUuid,
            device_id: cartStore.deviceId,
            note: cartStore.orderNote,
            items: cartStore.items.map(i => ({
                menu_item_id: i.id,
                quantity: i.quantity,
                price: i.price,
            })),
        });

        // Persist session UUID from backend so future orders link to same session
        if (response.session_uuid) {
            cartStore.setSessionUuid(response.session_uuid);
        }

        cartStore.clearCart();
        step.value = 'success';
    } catch {
        toast.error('Failed to place order. Please try again.');
    } finally {
        isPlacingOrder.value = false;
    }
}

function goToOrders() {
    cartStore.closeCart();
    step.value = 'cart';
    router.push({
        name: 'orders',
        params: {
            restaurant_slug: route.params.restaurant_slug,
            table_uuid: route.params.table_uuid,
        },
    });
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.sheet-enter-active {
    transition: transform 0.35s cubic-bezier(0.32, 0.72, 0, 1);
}

.sheet-leave-active {
    transition: transform 0.25s ease-in;
}

.sheet-enter-from,
.sheet-leave-to {
    transform: translateY(100%);
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes bounce-once {

    0%,
    100% {
        transform: translateY(0);
    }

    40% {
        transform: translateY(-18px);
    }

    60% {
        transform: translateY(-8px);
    }
}

.animate-bounce-once {
    animation: bounce-once 0.7s ease 0.1s both;
}
</style>