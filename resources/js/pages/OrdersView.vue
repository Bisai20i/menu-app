<template>
  <div class="min-h-screen bg-gray-50">

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-gray-100">
      <div class="max-w-screen-xl mx-auto px-4 lg:px-8 h-16 flex items-center gap-3">
        <button
          class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors shrink-0"
          @click="goBack">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>
        <div class="flex-1 min-w-0">
          <h1 class="font-bold text-gray-900 text-base leading-tight">My Orders</h1>
          <p v-if="cartStore.tableNumber" class="text-xs text-gray-400">Table {{ cartStore.tableNumber }}</p>
        </div>
        <!-- Refresh -->
        <button
          class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors"
          :class="{ 'animate-spin': isRefreshing }" @click="loadOrders(true)">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 4v6h6M23 20v-6h-6" />
            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15" />
          </svg>
        </button>
      </div>
    </header>

    <!-- No session yet -->
    <div v-if="!cartStore.sessionUuid && !isLoading"
      class="flex flex-col items-center justify-center gap-4 py-24 px-8 text-center">
      <div class="text-6xl">🍽️</div>
      <h2 class="font-bold text-gray-800 text-xl">No orders yet</h2>
      <p class="text-sm text-gray-400 max-w-[220px] leading-relaxed">
        Browse the menu and place your first order to get started.
      </p>
      <button
        class="mt-2 bg-primary text-white font-semibold px-6 py-3 rounded-2xl hover:bg-primary-dark transition-colors text-sm"
        @click="goBack">Browse Menu</button>
    </div>

    <!-- Loading -->
    <div v-else-if="isLoading" class="flex flex-col items-center justify-center gap-3 py-24">
      <div class="w-10 h-10 border-2 border-primary-light border-t-primary rounded-full animate-spin" />
      <p class="text-sm text-gray-400 font-medium">Loading orders...</p>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="flex flex-col items-center justify-center gap-4 py-24 px-8 text-center">
      <span class="text-5xl">😕</span>
      <p class="font-semibold text-gray-700">Couldn't load orders</p>
      <p class="text-sm text-gray-400">{{ error }}</p>
      <button
        class="bg-primary text-white font-semibold px-6 py-2.5 rounded-2xl hover:bg-primary-dark transition-colors text-sm"
        @click="loadOrders">Try Again</button>
    </div>

    <!-- Orders list -->
    <template v-else>
      <div class="max-w-screen-xl mx-auto px-4 lg:px-8 py-5 flex flex-col gap-4 pb-24">

        <!-- Session summary banner -->
        <div class="bg-gradient-to-r from-primary to-primary rounded-2xl p-4 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-semibold opacity-80 uppercase tracking-wider">Session Total</p>
              <p class="text-2xl font-black mt-0.5">Rs. {{ fmt(sessionTotal) }}</p>
            </div>
            <div class="text-right">
              <p class="text-xs font-semibold opacity-80 uppercase tracking-wider">Orders</p>
              <p class="text-2xl font-black mt-0.5">{{ orders.length }}</p>
            </div>
          </div>
          <div class="mt-3 pt-3 border-t border-white/20 flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-green-300 animate-pulse" />
            <p class="text-xs font-medium opacity-90">Session active · Table {{ cartStore.tableNumber }}</p>
          </div>
        </div>

        <!-- Empty orders (session exists but no orders somehow) -->
        <div v-if="!orders.length" class="flex flex-col items-center gap-3 py-12 text-center">
          <span class="text-5xl">📋</span>
          <p class="font-semibold text-gray-700">No orders in this session</p>
          <p class="text-sm text-gray-400">Your placed orders will appear here.</p>
        </div>

        <!-- Order cards -->
        <div v-for="(order, index) in orders" :key="order.uuid"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <!-- Order card header -->
          <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <span class="text-sm font-black text-primary">#{{ orders.length - index }}</span>
              </div>
              <div>
                <p class="font-bold text-gray-800 text-sm">Order {{ orders.length - index }}</p>
                <p class="text-xs text-gray-400">{{ formatTime(order.created_at) }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <!-- Payment badge -->
              <span class="text-xs font-semibold px-2.5 py-1 rounded-full" :class="order.is_paid
                ? 'bg-green-100 text-green-700'
                : 'bg-gray-100 text-gray-500'">
                {{ order.is_paid ? '✓ Paid' : 'Unpaid' }}
              </span>
              <!-- Status badge -->
              <span class="text-xs font-bold px-2.5 py-1 rounded-full" :class="statusClass(order.status)">
                {{ statusLabel(order.status) }}
              </span>
            </div>
            <!-- Cancel Button -->
            <button
              v-if="canCancel(order)"
              class="text-[10px] font-bold text-red-500 hover:text-red-600 bg-red-50 px-2 py-1 rounded-lg transition-colors border border-red-100"
              @click="confirmCancelOrder(order.uuid)">
              Cancel Order
            </button>
          </div>

          <!-- Status progress bar -->
          <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center gap-1">
              <template v-for="(s, i) in statusSteps" :key="s.key">
                <div class="flex-1 flex flex-col items-center gap-1">
                  <div class="w-full h-1.5 rounded-full transition-all duration-500"
                    :class="isStepDone(order.status, s.key) ? 'bg-primary' : 'bg-gray-200'" />
                  <p class="text-[10px] font-semibold transition-colors"
                    :class="isStepDone(order.status, s.key) ? 'text-primary' : 'text-gray-300'">{{ s.label }}</p>
                </div>
                <div v-if="i < statusSteps.length - 1" class="w-2 shrink-0" />
              </template>
            </div>
          </div>

          <!-- Order items -->
          <div class="divide-y divide-gray-50">
            <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between px-4 py-3 gap-3">
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center shrink-0 text-lg">
                  {{ getEmoji(item.menu_item) }}
                </div>
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-gray-800 line-clamp-1">
                    {{ item.menu_item?.name ?? '—' }}
                  </p>
                  <p class="text-xs text-gray-400">Rs. {{ fmt(item.unit_price) }} × {{ item.quantity }}</p>
                </div>
              </div>
              <p class="text-sm font-bold text-gray-800 shrink-0">Rs. {{ fmt(item.subtotal) }}</p>
            </div>
          </div>

          <!-- Order card footer -->
          <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-t border-gray-100">
            <p v-if="order.note" class="text-xs text-gray-400 italic flex-1 truncate mr-3">
              "{{ order.note }}"
            </p>
            <p v-else class="flex-1" />
            <p class="font-black text-gray-800 text-sm shrink-0">
              Rs. {{ fmt(order.total_amount) }}
            </p>
          </div>
        </div>

      </div>
    </template>

    <!-- Cancel Confirmation Modal -->
    <Transition name="fade">
      <div v-if="showCancelModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
          <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Cancel Order?</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to cancel this order? This action cannot be undone.</p>
            <div class="flex gap-3">
              <button class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors" @click="closeCancelModal">Keep Order</button>
              <button class="flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-colors shadow-sm shadow-red-200" @click="executeCancelOrder">Yes, Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Payment QR Modal -->
    <Transition name="fade">
      <div v-if="showPaymentQrModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
          <div class="p-5 border-b border-gray-100 flex items-start justify-between gap-3">
            <div>
              <h3 class="text-lg font-bold text-gray-900">Payment QR</h3>
              <p class="text-sm text-gray-500 mt-0.5">Show and download to proceed payment.</p>
            </div>
            <button
              class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors"
              @click="closePaymentQrModal"
              aria-label="Close payment QR"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="p-5">
            <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-center overflow-hidden">
              <img
                v-if="paymentQrSrc"
                :src="paymentQrSrc"
                alt="Payment QR"
                class="w-56 h-56 sm:w-64 sm:h-64 object-contain"
              />
              <div v-else class="text-sm text-gray-500 py-10 text-center">
                Payment QR unavailable.
              </div>
            </div>

            <button
              v-if="paymentQrSrc"
              class="mt-4 w-full bg-primary text-white font-semibold px-4 py-3 rounded-2xl hover:bg-primary-dark transition-colors shadow-sm"
              @click="downloadPaymentQr"
            >
              Download QR
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Floating Payment QR Button -->
    <div
      v-if="shouldShowPaymentQrButton"
      class="fixed right-4 z-30"
      style="bottom: calc(5rem + env(safe-area-inset-bottom));"
    >
      <button
        class="bg-primary text-white font-semibold px-4 py-3 rounded-2xl hover:bg-primary-dark transition-colors text-sm shadow-lg"
        @click="openPaymentQrModal"
      >
        Show Payment QR
      </button>
    </div>

    <!-- Floating Cart FAB -->
    <CartFab />

    <!-- Cart Bottom Sheet -->
    <CartBottomSheet />

    <!-- Bottom nav -->
    <BottomNav />

    <!-- Toast -->
    <ToastNotification />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCartStore } from '../stores/cart.js';
import { useToast } from '../composables/useToast.js';
import { menuApi } from '../services/api.js';
import { useOrderUpdates } from '../composables/useOrderUpdates.js';
import BottomNav from '../components/ui/BottomNav.vue';
import ToastNotification from '../components/ui/ToastNotification.vue';
import CartFab from '../components/cart/CartFab.vue';
import CartBottomSheet from '../components/cart/CartBottomSheet.vue';

const route = useRoute();
const router = useRouter();
const cartStore = useCartStore();
const toast = useToast();

const orders = ref([]);
const paymentQr = ref(null);
const showPaymentQrModal = ref(false);
const isLoading = ref(false);
const isRefreshing = ref(false);
const error = ref(null);

const showCancelModal = ref(false);
const orderToCancel = ref(null);

const statusSteps = [
  { key: 'pending', label: 'Placed' },
  { key: 'confirmed', label: 'Confirmed' },
  { key: 'served', label: 'Served' },
];

const statusOrder = { pending: 0, confirmed: 1, served: 2 };

function isStepDone(currentStatus, stepKey) {
  return statusOrder[currentStatus] >= statusOrder[stepKey];
}

const sessionTotal = computed(() =>
  orders.value.reduce((sum, o) => sum + Number(o.total_amount), 0)
);

function normalizePaymentQrSrc(raw) {
  const val = (raw ?? '').toString().trim();
  if (!val) return null;

  if (val.startsWith('data:')) return val;
  if (/^https?:\/\//i.test(val)) return val;
  if (val.startsWith('/')) return `${window.location.origin}${val}`;

  // If admin pasted raw base64 without the `data:image/...` prefix, try PNG.
  const looksLikeBase64 = /^[A-Za-z0-9+/]+={0,2}$/.test(val);
  if (looksLikeBase64 && val.length > 50) return `data:image/png;base64,${val}`;

  // Stored as a relative `storage/...` path or a `payment_qrs/...` path.
  // Laravel usually serves it under `/storage/{path}`.
  const withoutStoragePrefix = val.startsWith('storage/')
    ? val.slice('storage/'.length)
    : val;
  return `${window.location.origin}/storage/${withoutStoragePrefix}`;
}

const paymentQrSrc = computed(() => normalizePaymentQrSrc(paymentQr.value));

const shouldShowPaymentQrButton = computed(() => {
  if (!paymentQr.value) return false;
  if (!cartStore.sessionUuid) return false;
  if (!orders.value.length) return false;

  // Requirement: at least one order is not in the `pending` state.
  return orders.value.some(o => (o.status ?? 'pending') !== 'pending');
});

async function loadOrders(refresh = false) {
  if (!cartStore.sessionUuid) return;

  if (refresh) {
    isRefreshing.value = true;
  } else {
    isLoading.value = true;
  }
  error.value = null;
  paymentQr.value = null;

  try {
    const data = await menuApi.getSessionOrders(cartStore.sessionUuid);
    // Most recent orders first
    orders.value = (data.orders ?? []).sort(
      (a, b) => new Date(b.created_at) - new Date(a.created_at)
    );
      paymentQr.value = data.payment_qr ?? null;
  } catch (err) {
    error.value = err.message;
  } finally {
    isLoading.value = false;
    isRefreshing.value = false;
  }
}

function openPaymentQrModal() {
  showPaymentQrModal.value = true;
}

function closePaymentQrModal() {
  showPaymentQrModal.value = false;
}

async function downloadPaymentQr() {
  const src = paymentQrSrc.value;
  if (!src) return;

  try {
    // data URLs can be downloaded directly without CORS.
    if (src.startsWith('data:')) {
      const mimeMatch = src.match(/^data:([^;]+);base64,/i);
      const mime = mimeMatch?.[1] ?? 'image/png';
      const ext = mime.includes('jpeg') ? 'jpg' : (mime.split('/')[1] || 'png');
      const filename = `payment-qr.${ext}`;

      const link = document.createElement('a');
      link.href = src;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      link.remove();
      return;
    }

    // For normal URLs, fetch as blob so we can trigger a download.
    const res = await fetch(src);
    if (!res.ok) throw new Error('Failed to fetch payment QR.');

    const blob = await res.blob();
    const url = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = url;
    link.download = 'payment-qr.png';
    document.body.appendChild(link);
    link.click();
    link.remove();

    window.URL.revokeObjectURL(url);
  } catch (err) {
    toast.error(err?.message || 'Failed to download payment QR.');
  }
}

// Page-level handler: only merge the updated order into the local list.
// Toast + sound are handled globally by useOrderUpdates in App.vue.
function handleOrderUpdate(updatedOrder) {
  const index = orders.value.findIndex(o => o.uuid === updatedOrder.uuid);
  if (index !== -1) {
    orders.value[index] = { ...orders.value[index], ...updatedOrder };
  }
}

// Wire into the global composable to keep local state in sync
useOrderUpdates(handleOrderUpdate);

function confirmCancelOrder(uuid) {
  orderToCancel.value = uuid;
  showCancelModal.value = true;
}

function closeCancelModal() {
  showCancelModal.value = false;
  orderToCancel.value = null;
}

async function executeCancelOrder() {
  if (!orderToCancel.value) return;
  const uuid = orderToCancel.value;
  closeCancelModal();
  
  try {
    await menuApi.cancelOrder(uuid);
    toast.success('Order cancelled successfully');
    loadOrders(true);
  } catch (err) {
    toast.error(err.message || 'Failed to cancel order');
  }
}

function canCancel(order) {
  if (order.status !== 'pending') return false;
  
  const createdAt = new Date(order.created_at);
  const now = new Date();
  const diffInMinutes = (now - createdAt) / 1000 / 60;
  
  return diffInMinutes < 2;
}

function goBack() {
  router.push({
    name: 'menu',
    params: {
      restaurant_slug: route.params.restaurant_slug,
      table_uuid: route.params.table_uuid,
    },
  });
}

function fmt(val) { return Number(val ?? 0).toFixed(2); }

function formatTime(dateStr) {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function getEmoji(item) {
  const name = item?.name?.toLowerCase() || '';
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

function statusLabel(status) {
  return { 
    pending: '⏳ Pending', 
    confirmed: '👨‍🍳 Preparing', 
    served: '✓ Served',
    cancelled: '✕ Cancelled'
  }[status] ?? status;
}

function statusClass(status) {
  return {
    pending: 'bg-amber-100 text-amber-700',
    confirmed: 'bg-blue-100 text-blue-700',
    served: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
  }[status] ?? 'bg-gray-100 text-gray-500';
}

onMounted(() => {
  if (!cartStore.sessionUuid) {
    const tableUuid = route.params.table_uuid;
    if (tableUuid) {
      if (!cartStore.tableUuid) cartStore.setTableInfo(null, tableUuid, null);
      cartStore.rehydrateSession();
    }
  }
  loadOrders();
});
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  line-clamp: 1;
  overflow: hidden;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>