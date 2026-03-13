<template>
    <div class="min-h-screen bg-gray-50 font-sans">

        <!-- ── Loading ─────────────────────────────────────────────── -->
        <div v-if="menuStore.isLoading" class="min-h-screen flex flex-col items-center justify-center gap-4 bg-white">
            <div
                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-amber-400 flex items-center justify-center shadow-lg animate-pulse">
                <span class="text-2xl">🍽️</span>
            </div>
            <p class="text-gray-500 font-medium text-sm animate-pulse">Loading menu...</p>
        </div>

        <!-- ── Error ─────────────────────────────────────────────────── -->
        <div v-else-if="menuStore.error"
            class="min-h-screen flex flex-col items-center justify-center gap-4 p-8 bg-white">
            <span class="text-5xl">😕</span>
            <h2 class="font-bold text-gray-800 text-xl">Something went wrong</h2>
            <p class="text-gray-400 text-sm text-center">{{ menuStore.error }}</p>
            <button
                class="bg-orange-500 text-white font-semibold px-6 py-3 rounded-2xl hover:bg-orange-600 transition-colors"
                @click="fetchMenu">Try Again</button>
        </div>

        <!-- ── Main Content ──────────────────────────────────────────── -->
        <template v-else-if="menuStore.restaurant">
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-40 bg-white border-b border-gray-100">
                <div class="max-w-screen-xl mx-auto px-4 lg:px-8 h-16 flex items-center justify-between gap-3">
                    <!-- Restaurant info -->
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-400 to-amber-400 flex items-center justify-center shrink-0 shadow-sm">
                            <span class="text-base">🍽️</span>
                        </div>
                        <div class="min-w-0">
                            <h1 class="font-bold text-md text-gray-900 leading-tight truncate text-lg md:text-xl">{{
                                menuStore.restaurant.name }}</h1>
                            <p v-if="tableNumber" class="text-xs text-gray-400 leading-tight">Table {{ tableNumber }}
                            </p>
                        </div>
                    </div>

                    <!-- Right actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Search toggle -->
                        <button class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                            :class="showSearch ? 'bg-orange-100 text-orange-500' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            @click="toggleSearch">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                        </button>

                        <!-- Call waiter -->
                        <button :disabled="isCallingWaiter"
                            class="flex items-center gap-1.5 bg-orange-500 text-white text-xs font-bold px-3 py-2 rounded-xl hover:bg-orange-600 active:scale-95 transition-all disabled:opacity-60 shadow-sm shadow-orange-200"
                            @click="callWaiter">
                            <span v-if="isCallingWaiter"
                                class="w-3 h-3 border border-white/50 border-t-white rounded-full animate-spin shrink-0" />
                            <span v-else>🔔</span>
                            <span class="hidden sm:inline">{{ isCallingWaiter ? 'Calling...' : 'Call Waiter' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Search bar -->
                <Transition name="search-bar">
                    <div v-if="showSearch" class="px-4 lg:px-8 pb-3 max-w-screen-xl mx-auto">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            <input ref="searchInputRef" v-model="searchQuery" type="text"
                                placeholder="Search dishes, drinks..."
                                class="w-full bg-gray-100 rounded-xl pl-9 pr-10 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:bg-white transition-all"
                                @input="menuStore.setSearch(searchQuery)" />
                            <button v-if="searchQuery"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                @click="clearSearch">✕</button>
                        </div>
                    </div>
                </Transition>
            </header>

            <!-- ── Mobile: horizontal scrollable category pills (hidden on lg+) ── -->
            <div class="lg:hidden">
                <CategoryPills v-if="!searchQuery" :categories="menuStore.categories" :active="menuStore.activeCategory"
                    @select="menuStore.setCategory($event)" />
            </div>

            <!-- ── Page body: container with desktop sidebar layout ── -->
            <div class="max-w-screen-xl mx-auto px-4 lg:px-8 pb-40">
                <div class="lg:flex lg:gap-8 lg:items-start">

                    <!-- ── Desktop: vertical category sidebar (1/3, hidden on mobile) ── -->
                    <aside class="hidden lg:block lg:w-1/3 xl:w-1/4 sticky top-[65px] self-start">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <h2 class="font-bold text-gray-700 text-xs uppercase tracking-widest">Menu</h2>
                            </div>
                            <nav class="p-2 flex flex-col gap-0.5">
                                <button
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 text-left"
                                    :class="menuStore.activeCategory === 'all'
                                        ? 'bg-orange-500 text-white shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50'" @click="menuStore.setCategory('all')">
                                    <span class="text-base leading-none">🍽️</span>
                                    <span class="flex-1">All Items</span>
                                    <span class="text-xs font-bold px-1.5 py-0.5 rounded-full"
                                        :class="menuStore.activeCategory === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400'">{{
                                            menuStore.allItems.length }}</span>
                                </button>

                                <button v-for="cat in menuStore.categories" :key="cat.id"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 text-left"
                                    :class="menuStore.activeCategory === cat.id
                                        ? 'bg-orange-500 text-white shadow-sm'
                                        : 'text-gray-600 hover:bg-gray-50'" @click="menuStore.setCategory(cat.id)">
                                    <span class="text-base leading-none">{{ getCategoryEmoji(cat.name) }}</span>
                                    <span class="flex-1 truncate">{{ cat.name }}</span>
                                    <span class="text-xs font-bold px-1.5 py-0.5 rounded-full shrink-0"
                                        :class="menuStore.activeCategory === cat.id ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400'">{{
                                            menuStore.allItems.filter(i => i.menu_category_id === cat.id).length}}</span>
                                </button>
                            </nav>
                        </div>
                    </aside>

                    <!-- ── Main content (2/3 on desktop, full width on mobile) ── -->
                    <main class="lg:flex-1 min-w-0">
                        <!-- Featured carousel (hide during search) -->
                        <div v-if="!searchQuery" class="pt-4 lg:pt-6">
                            <FeaturedCarousel :items="menuStore.featuredItems" @add-to-cart="addToCart"
                                @view-item="viewItem" />
                        </div>

                        <!-- Menu sections -->
                        <div class="flex flex-col gap-6 mt-2 lg:mt-4">
                            <!-- Search results header -->
                            <div v-if="searchQuery" class="pt-2">
                                <p class="text-sm text-gray-500">
                                    <span class="font-semibold text-gray-800">{{ menuStore.filteredItems.length
                                    }}</span>
                                    result{{ menuStore.filteredItems.length !== 1 ? 's' : '' }} for
                                    "<span class="text-orange-500 font-medium">{{ searchQuery }}</span>"
                                </p>
                            </div>

                            <!-- Empty search results -->
                            <div v-if="searchQuery && !menuStore.filteredItems.length"
                                class="flex flex-col items-center gap-3 py-12">
                                <span class="text-5xl">🔍</span>
                                <p class="font-semibold text-gray-700">No dishes found</p>
                                <p class="text-sm text-gray-400 text-center">Try searching something else</p>
                            </div>

                            <!-- Category groups or search results -->
                            <template v-if="!searchQuery">
                                <section v-for="group in menuStore.groupedByCategory" :key="group.id">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="font-bold text-gray-800 text-base flex items-center gap-2">
                                            <span>{{ getCategoryEmoji(group.name) }}</span>
                                            {{ group.name }}
                                        </h2>
                                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{
                                            group.items.length }}</span>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3">
                                        <MenuItemCard v-for="item in group.items" :key="item.id" :item="item"
                                            :quantity="cartStore.getItemQuantity(item.id)" @add="addToCart"
                                            @remove="cartStore.removeItem($event.id)" @view="viewItem" />
                                    </div>
                                </section>
                            </template>

                            <!-- Search results flat list -->
                            <template v-else>
                                <div class="grid grid-cols-1 gap-3">
                                    <MenuItemCard v-for="item in menuStore.filteredItems" :key="item.id" :item="item"
                                        :quantity="cartStore.getItemQuantity(item.id)" @add="addToCart"
                                        @remove="cartStore.removeItem($event.id)" @view="viewItem" />
                                </div>
                            </template>
                        </div>
                    </main>

                </div>
            </div>
        </template>

        <!-- ── Item Detail Modal ─────────────────────────────────────── -->
        <Transition name="fade">
            <div v-if="selectedItem"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
                @click.self="selectedItem = null">
                <div class="bg-white w-full sm:max-w-md sm:rounded-3xl rounded-t-3xl overflow-hidden shadow-2xl">
                    <div class="relative h-56 bg-gradient-to-br from-orange-100 to-yellow-50">
                        <img v-if="selectedItem.image" :src="selectedItem.image" :alt="selectedItem.name"
                            class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-7xl">
                            {{ getEmoji(selectedItem) }}
                        </div>
                        <button
                            class="absolute top-4 right-4 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center text-gray-600 shadow-sm hover:bg-white"
                            @click="selectedItem = null">✕</button>
                        <div v-if="selectedItem.is_featured"
                            class="absolute top-4 left-4 bg-amber-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                            ⭐ Chef's Pick
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <h2 class="font-bold text-gray-900 text-xl leading-tight">{{ selectedItem.name }}</h2>
                            <span class="font-black text-orange-500 text-xl shrink-0">Rs. {{
                                formatPrice(selectedItem.price)
                            }}</span>
                        </div>

                        <p v-if="selectedItem.description" class="text-sm text-gray-500 leading-relaxed !mb-3">
                            {{ selectedItem.description }}
                        </p>

                        <!-- Dietary tags -->
                        <div v-if="getDietaryTags(selectedItem).length" class="flex flex-wrap gap-1.5 mb-4">
                            <span v-for="tag in getDietaryTags(selectedItem)" :key="tag"
                                class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">{{
                                    tag
                                }}</span>
                        </div>

                        <!-- Availability -->
                        <div v-if="!selectedItem.is_available"
                            class="bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-500 text-center font-medium mb-4">
                            Currently unavailable
                        </div>

                        <!-- Add to cart controls -->
                        <template v-else>
                            <div v-if="cartStore.getItemQuantity(selectedItem.id) > 0"
                                class="flex items-center justify-between bg-orange-50 rounded-2xl p-3 mb-3">
                                <button
                                    class="w-10 h-10 rounded-xl bg-white border border-orange-200 text-orange-500 text-xl font-bold flex items-center justify-center shadow-sm active:scale-90 transition-transform"
                                    @click="cartStore.removeItem(selectedItem.id)">−</button>
                                <div class="text-center">
                                    <p class="font-bold text-gray-800 text-lg">{{
                                        cartStore.getItemQuantity(selectedItem.id) }}</p>
                                    <p class="text-xs text-gray-400">in cart</p>
                                </div>
                                <button
                                    class="w-10 h-10 rounded-xl bg-orange-500 text-white text-xl font-bold flex items-center justify-center shadow-sm active:scale-90 transition-transform"
                                    @click="addToCart(selectedItem)">+</button>
                            </div>

                            <button v-else
                                class="w-full bg-orange-500 text-white font-bold py-4 rounded-2xl text-base hover:bg-orange-600 active:scale-[0.98] transition-all shadow-lg shadow-orange-200"
                                @click="addToCart(selectedItem)">
                                Add to Cart - Rs. {{ formatPrice(selectedItem.price) }}
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Floating Cart FAB -->
        <CartFab />

        <!-- Cart Bottom Sheet -->
        <CartBottomSheet />

        <!-- Bottom Nav -->
        <BottomNav />

        <!-- Toast notifications -->
        <ToastNotification />
    </div>
</template>

<script setup>
import { ref, nextTick, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useMenuStore } from '../stores/menu.js';
import { useCartStore } from '../stores/cart.js';
import { menuApi } from '../services/api.js';
import { useToast } from '../composables/useToast.js';

import CategoryPills from '../components/menu/CategoryPills.vue';
import FeaturedCarousel from '../components/menu/FeaturedCarousel.vue';
import MenuItemCard from '../components/menu/MenuItemCard.vue';
import CartFab from '../components/cart/CartFab.vue';
import CartBottomSheet from '../components/cart/CartBottomSheet.vue';
import ToastNotification from '../components/ui/ToastNotification.vue';
import BottomNav from '../components/ui/BottomNav.vue';

const route = useRoute();
const menuStore = useMenuStore();
const cartStore = useCartStore();
const toast = useToast();

const showSearch = ref(false);
const searchQuery = ref('');
const searchInputRef = ref(null);
const selectedItem = ref(null);
const isCallingWaiter = ref(false);
const tableNumber = ref(null);

async function fetchMenu() {
    const { restaurant_slug, table_uuid } = route.params;
    await menuStore.loadMenu(restaurant_slug, table_uuid, cartStore.deviceId);
    if (menuStore.restaurant) {
        const tableInfo = menuStore.tableData;
        
        tableNumber.value = tableInfo?.table_number || null;
        cartStore.setTableInfo(
            menuStore.restaurant.id,
            table_uuid,
            tableNumber.value
        );

        // If the backend found an active session for this device, use it directly
        if (tableInfo?.active_session_uuid) {
            cartStore.setSessionUuid(tableInfo.active_session_uuid);
        } else {
            // Rehydrate sessionUuid from localStorage if it exists for this table.
            // Then silently validate it against the backend — if the session was
            // closed by staff (paid/cancelled), wipe the stale reference so the
            // next order starts a fresh session.
            const storedSession = cartStore.rehydrateSession();
            if (storedSession) {
                try {
                    await menuApi.getSessionOrders(storedSession);
                    // Session still active — nothing more to do, sessionUuid is already set
                } catch {
                    // 404 means session closed or not found — clear stale localStorage entry
                    cartStore.clearSession();
                }
            }
        }
    }
}

async function callWaiter() {
    isCallingWaiter.value = true;
    try {
        await menuApi.callWaiter(menuStore.restaurant?.id, route.params.table_uuid);
        toast.success('Waiter has been notified! 🛎️');
    } catch {
        // Even on failure, show feedback — could be a toast-only action
        toast.info('Waiter has been called to your table 🛎️');
    } finally {
        isCallingWaiter.value = false;
    }
}

function toggleSearch() {
    showSearch.value = !showSearch.value;
    if (showSearch.value) {
        nextTick(() => searchInputRef.value?.focus());
    } else {
        clearSearch();
    }
}

function clearSearch() {
    searchQuery.value = '';
    menuStore.setSearch('');
}

function addToCart(item) {
    cartStore.addItem(item);
    toast.success(`${item.name} added to cart`);
}

function viewItem(item) {
    selectedItem.value = item;
}

function formatPrice(price) {
    return Number(price).toFixed(2);
}

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
    if (name.includes('rice') || name.includes('biryani')) return '🍚';
    return '🍽️';
}

function getDietaryTags(item) {
    if (!item.dietary_info) return [];
    if (Array.isArray(item.dietary_info)) return item.dietary_info;
    try { return JSON.parse(item.dietary_info); } catch { return []; }
}

function getCategoryEmoji(name) {
    const n = name?.toLowerCase() || '';
    if (n.includes('starter') || n.includes('appetizer')) return '🥗';
    if (n.includes('main') || n.includes('entree')) return '🍛';
    if (n.includes('pizza')) return '🍕';
    if (n.includes('burger')) return '🍔';
    if (n.includes('dessert')) return '🍰';
    if (n.includes('drink') || n.includes('beverage')) return '🥤';
    if (n.includes('breakfast')) return '🍳';
    if (n.includes('grill') || n.includes('bbq')) return '🥩';
    return '🍽️';
}

onMounted(fetchMenu);
</script>

<style scoped>
.search-bar-enter-active,
.search-bar-leave-active {
    transition: all 0.2s ease;
}

.search-bar-enter-from,
.search-bar-leave-to {
    opacity: 0;
    transform: translateY(-8px);
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