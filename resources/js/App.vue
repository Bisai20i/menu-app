<template>
    <div id="app-shell">
        <!-- Top Header -->
        <header class="top-nav">
            <div class="top-nav__left">
                <div class="restaurant-badge">
                    <span class="restaurant-badge__icon">🍽️</span>
                    <span class="restaurant-badge__name">{{ restaurant.name }}</span>
                </div>
                <div v-if="tableNumber" class="table-chip">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    Table {{ tableNumber }}
                </div>
            </div>
            <div class="top-nav__right">
                <router-link to="/orders" class="orders-btn" :class="{ active: $route.path === '/orders' }">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    <span>My Orders</span>
                    <span v-if="ordersStore.activeOrders.length" class="badge-dot">{{ ordersStore.activeOrders.length
                        }}</span>
                </router-link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <router-view v-slot="{ Component }">
                <transition name="fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <router-link to="/" class="bottom-nav__item" :class="{ active: $route.path === '/' }">
                <span class="bottom-nav__icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                </span>
                <span class="bottom-nav__label">Menu</span>
            </router-link>

            <router-link to="/cart" class="bottom-nav__item cart-tab" :class="{ active: $route.path === '/cart' }">
                <span class="bottom-nav__icon cart-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6" />
                    </svg>
                    <span v-if="cartStore.itemCount > 0" class="cart-count">{{ cartStore.itemCount }}</span>
                </span>
                <span class="bottom-nav__label">Cart</span>
            </router-link>

            <router-link to="/orders" class="bottom-nav__item" :class="{ active: $route.path === '/orders' }">
                <span class="bottom-nav__icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </span>
                <span class="bottom-nav__label">Orders</span>
            </router-link>
        </nav>
    </div>
</template>

<script setup>
import { computed, provide } from 'vue'
import { useCartStore } from './stores/CartStore'
import { useOrdersStore } from './stores/OrdersStore'
import { restaurant } from './data/MenuData'

const cartStore = useCartStore()
const ordersStore = useOrdersStore()

// Read table number from URL query params
const tableNumber = computed(() => {
    const params = new URLSearchParams(window.location.search)
    return params.get('table') || null
})

provide('tableNumber', tableNumber)
</script>

<style scoped>
#app-shell {
    display: flex;
    flex-direction: column;
    min-height: 100dvh;
    position: relative;
}

/* ─── Top Nav ─────────────────────────────────────────────────────── */
.top-nav {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: var(--max-width);
    height: var(--nav-height);
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px;
    z-index: 100;
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.92);
}

.top-nav__left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.restaurant-badge {
    display: flex;
    align-items: center;
    gap: 6px;
}

.restaurant-badge__icon {
    font-size: 1.2rem;
}

.restaurant-badge__name {
    font-family: var(--font-display);
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--ink);
}

.table-chip {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--brand);
    background: var(--brand-bg);
    border: 1px solid rgba(200, 82, 42, 0.2);
    padding: 3px 8px;
    border-radius: var(--radius-full);
}

.orders-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--ink-soft);
    padding: 6px 12px;
    border-radius: var(--radius-full);
    background: var(--surface-2);
    border: 1px solid var(--border);
    transition: all 0.15s;
    position: relative;
}

.orders-btn:hover {
    background: var(--brand-bg);
    color: var(--brand);
    border-color: rgba(200, 82, 42, 0.2);
}

.orders-btn.active {
    color: var(--brand);
    background: var(--brand-bg);
    border-color: rgba(200, 82, 42, 0.2);
}

.badge-dot {
    min-width: 18px;
    height: 18px;
    background: var(--brand);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    border-radius: var(--radius-full);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}

/* ─── Main Content ─────────────────────────────────────────────────── */
.main-content {
    flex: 1;
    padding-top: var(--nav-height);
    padding-bottom: var(--bottom-nav-height);
}

/* ─── Bottom Nav ──────────────────────────────────────────────────── */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: var(--max-width);
    height: var(--bottom-nav-height);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    z-index: 100;
    padding-bottom: env(safe-area-inset-bottom);
}

.bottom-nav__item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 8px 0;
    color: var(--ink-muted);
    transition: color 0.15s;
}

.bottom-nav__item.active {
    color: var(--brand);
}

.bottom-nav__item:hover {
    color: var(--ink-soft);
}

.bottom-nav__item.active:hover {
    color: var(--brand);
}

.bottom-nav__icon {
    position: relative;
    line-height: 1;
}

.bottom-nav__label {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.01em;
}

.cart-icon-wrap {
    position: relative;
}

.cart-count {
    position: absolute;
    top: -6px;
    right: -8px;
    min-width: 18px;
    height: 18px;
    background: var(--brand);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid var(--surface);
    animation: pop 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes pop {
    from {
        transform: scale(0.5);
    }

    to {
        transform: scale(1);
    }
}
</style>