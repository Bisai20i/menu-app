<template>
    <div class="cart-view">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Your Cart</h1>
            <div v-if="cartStore.items.length" class="header-actions">
                <span class="item-count-label">{{ cartStore.itemCount }} item{{ cartStore.itemCount !== 1 ? 's' : ''
                    }}</span>
                <button class="clear-btn" @click="showClearConfirm = true">Clear all</button>
            </div>
        </div>

        <!-- Empty Cart -->
        <div v-if="!cartStore.items.length" class="empty-state">
            <div class="emoji">🛒</div>
            <h3>Your cart is empty</h3>
            <p>Browse the menu and add items to get started</p>
            <router-link to="/" class="btn btn-primary" style="margin-top:8px">
                Browse Menu
            </router-link>
        </div>

        <!-- Cart Items -->
        <div v-else class="cart-content">
            <TransitionGroup name="list" tag="div" class="cart-items">
                <div v-for="item in cartStore.items" :key="item.id" class="cart-item">
                    <div class="cart-item__img-wrap">
                        <img v-if="item.image" :src="item.image" :alt="item.name" class="cart-item__img" />
                        <div v-else class="cart-item__img-placeholder">🍽️</div>
                    </div>
                    <div class="cart-item__info">
                        <div class="cart-item__name">{{ item.name }}</div>
                        <div class="cart-item__unit-price">${{ item.price.toFixed(2) }} each</div>
                        <div class="cart-item__controls">
                            <div class="qty-stepper">
                                <button class="qty-btn" @click="cartStore.decrementQuantity(item.id)">−</button>
                                <span class="qty-count">{{ item.quantity }}</span>
                                <button class="qty-btn" @click="cartStore.incrementQuantity(item.id)">+</button>
                            </div>
                            <button class="remove-btn" @click="cartStore.removeItem(item.id)" aria-label="Remove item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                    <path d="M10 11v6M14 11v6" />
                                    <path d="M9 6V4h6v2" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="cart-item__subtotal">${{ (item.price * item.quantity).toFixed(2) }}</div>
                </div>
            </TransitionGroup>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="summary-title">Order Summary</h2>

                <div class="summary-rows">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>${{ cartStore.subtotal.toFixed(2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Service Fee</span>
                        <span>${{ serviceFee.toFixed(2) }}</span>
                    </div>
                    <div class="divider"></div>
                    <div class="summary-row summary-row--total">
                        <span>Total</span>
                        <span>${{ total.toFixed(2) }}</span>
                    </div>
                </div>

                <!-- Special Notes -->
                <div class="notes-section">
                    <label class="notes-label" for="special-notes">Special Requests</label>
                    <textarea id="special-notes" v-model="specialNotes" class="notes-input"
                        placeholder="Allergies, dietary requirements, or any special requests..." rows="3"></textarea>
                </div>

                <!-- Place Order Button -->
                <button class="btn btn-primary place-order-btn" @click="handlePlaceOrder">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    Confirm & Place Order
                </button>

                <p class="order-note">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Your order will be sent directly to the kitchen
                </p>
            </div>
        </div>

        <!-- Clear Cart Confirm Modal -->
        <Transition name="fade">
            <div v-if="showClearConfirm" class="modal-overlay" @click.self="showClearConfirm = false">
                <div class="modal">
                    <h3 class="modal__title">Clear Cart?</h3>
                    <p class="modal__body">This will remove all {{ cartStore.itemCount }} items from your cart.</p>
                    <div class="modal__actions">
                        <button class="btn btn-secondary" @click="showClearConfirm = false">Cancel</button>
                        <button class="btn btn-danger" @click="clearCart">Clear Cart</button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Order Success Toast -->
        <Transition name="slide-up">
            <div v-if="showSuccess" class="success-toast">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                Order placed! Redirecting...
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, inject } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import { useOrdersStore } from '@/stores/ordersStore'

const router = useRouter()
const cartStore = useCartStore()
const ordersStore = useOrdersStore()
const tableNumber = inject('tableNumber')

const specialNotes = ref('')
const showClearConfirm = ref(false)
const showSuccess = ref(false)

const SERVICE_FEE_RATE = 0.05
const serviceFee = computed(() => cartStore.subtotal * SERVICE_FEE_RATE)
const total = computed(() => cartStore.subtotal + serviceFee.value)

function clearCart() {
    cartStore.clearCart()
    showClearConfirm.value = false
}

function handlePlaceOrder() {
    const orderId = ordersStore.placeOrder(
        cartStore.items,
        tableNumber?.value,
        total.value
    )
    ordersStore.simulateStatusUpdate(orderId)
    cartStore.clearCart()
    specialNotes.value = ''
    showSuccess.value = true
    setTimeout(() => {
        showSuccess.value = false
        router.push('/orders')
    }, 1500)
}
</script>

<style scoped>
.cart-view {
    background: var(--surface-2);
    min-height: 100dvh;
}

.page-header {
    background: var(--surface);
    padding: 20px 16px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.page-title {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--ink);
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.item-count-label {
    font-size: 0.8rem;
    color: var(--ink-muted);
}

.clear-btn {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--error);
    padding: 4px 10px;
    border-radius: var(--radius-full);
    background: var(--error-bg);
    transition: background 0.15s;
}

.clear-btn:hover {
    background: #FACBBE;
}

/* ─── Empty ─────────────────────────────────────────────────────────── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 80px 24px;
    text-align: center;
    gap: 10px;
}

.emoji {
    font-size: 3.5rem;
    margin-bottom: 4px;
}

.empty-state h3 {
    font-family: var(--font-display);
    font-size: 1.2rem;
    color: var(--ink-soft);
}

.empty-state p {
    font-size: 0.88rem;
    color: var(--ink-muted);
}

/* ─── Cart Content ─────────────────────────────────────────────────── */
.cart-content {
    padding: 12px 16px 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 12px;
    margin-bottom: 8px;
    transition: box-shadow 0.2s;
}

.cart-item:hover {
    box-shadow: var(--shadow-sm);
}

.cart-item__img-wrap {
    width: 72px;
    height: 72px;
    flex-shrink: 0;
    border-radius: var(--radius-md);
    overflow: hidden;
    background: var(--surface-3);
}

.cart-item__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item__img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.cart-item__info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.cart-item__name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cart-item__unit-price {
    font-size: 0.75rem;
    color: var(--ink-muted);
}

.cart-item__controls {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
}

.cart-item__subtotal {
    font-family: var(--font-display);
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--brand);
    flex-shrink: 0;
    align-self: flex-start;
    padding-top: 2px;
}

.remove-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: var(--radius-full);
    color: var(--ink-muted);
    background: var(--surface-2);
    transition: all 0.15s;
}

.remove-btn:hover {
    background: var(--error-bg);
    color: var(--error);
}

/* ─── Summary ──────────────────────────────────────────────────────── */
.order-summary {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.summary-title {
    font-family: var(--font-display);
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--ink);
}

.summary-rows {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.88rem;
    color: var(--ink-soft);
}

.summary-row--total {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--ink);
    font-family: var(--font-display);
}

.notes-label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--ink-soft);
    margin-bottom: 6px;
}

.notes-input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    padding: 10px 12px;
    font-size: 0.85rem;
    color: var(--ink);
    background: var(--surface-2);
    resize: none;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    font-family: var(--font-body);
}

.notes-input:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200, 82, 42, 0.1);
}

.place-order-btn {
    width: 100%;
    padding: 16px;
    font-size: 0.95rem;
    border-radius: var(--radius-xl);
}

.order-note {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.75rem;
    color: var(--ink-muted);
    text-align: center;
    justify-content: center;
}

/* ─── Modal ────────────────────────────────────────────────────────── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(26, 18, 8, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    z-index: 200;
}

.modal {
    background: var(--surface);
    border-radius: var(--radius-xl);
    padding: 24px;
    width: 100%;
    max-width: 320px;
    box-shadow: var(--shadow-lg);
    animation: modal-in 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modal-in {
    from {
        opacity: 0;
        transform: scale(0.92);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal__title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 8px;
}

.modal__body {
    font-size: 0.88rem;
    color: var(--ink-muted);
    margin-bottom: 20px;
}

.modal__actions {
    display: flex;
    gap: 10px;
}

.modal__actions .btn {
    flex: 1;
}

/* ─── Success Toast ─────────────────────────────────────────────────── */
.success-toast {
    position: fixed;
    bottom: calc(var(--bottom-nav-height) + 16px);
    left: 50%;
    transform: translateX(-50%);
    background: var(--success);
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: var(--radius-full);
    font-size: 0.88rem;
    font-weight: 600;
    box-shadow: 0 4px 16px rgba(45, 122, 79, 0.35);
    white-space: nowrap;
    z-index: 300;
}
</style>