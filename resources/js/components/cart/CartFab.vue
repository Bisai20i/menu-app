<template>
  <Transition name="cart-fab">
    <div
      v-if="cartStore.totalItems > 0"
      class="fixed bottom-15 left-0 right-0 z-30 px-4 pb-4 sm:bottom-10 sm:left-1/2 sm:right-auto sm:px-0 sm:pb-0 sm:-translate-x-1/2 sm:w-auto"
    >
      <button
        class="w-full sm:w-auto bg-primary text-white font-bold px-6 py-4 rounded-2xl shadow-md shadow-primary-dark/20 flex items-center justify-center gap-3 hover:bg-primary-dark active:scale-[0.98] transition-all"
        @click="cartStore.openCart()"
      >
        <!-- Cart icon with badge -->
        <div class="relative shrink-0">
          <span class="text-lg">🛒</span>
          <span class="absolute -top-2 -right-2 bg-white text-primary text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-sm">
            {{ cartStore.totalItems > 9 ? '9+' : cartStore.totalItems }}
          </span>
        </div>

        <span class="text-sm">View Cart</span>

        <div class="w-px h-4 bg-white/30" />

        <span class="text-sm font-extrabold">Rs. {{ formatPrice(cartStore.totalPrice) }}</span>
      </button>
    </div>
  </Transition>
</template>

<script setup>
import { useCartStore } from '../../stores/cart.js';
const cartStore = useCartStore();
function formatPrice(p) { return Number(p).toFixed(2); }
</script>

<style scoped>
.cart-fab-enter-active { transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); }
.cart-fab-leave-active { transition: all 0.2s ease-in; }
.cart-fab-enter-from, .cart-fab-leave-to { opacity: 0; transform: translateY(20px) scale(0.95); }
</style>