<template>
    <div class="orders-view">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">My Orders</h1>
            <div v-if="tableNumber" class="table-chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                Table {{ tableNumber }}
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!ordersStore.orders.length" class="empty-state">
            <div class="emoji">📋</div>
            <h3>No orders yet</h3>
            <p>Once you place an order it will appear here</p>
            <router-link to="/" class="btn btn-primary" style="margin-top:8px">
                Browse Menu
            </router-link>
        </div>

        <template v-else>
            <!-- Total Summary Banner -->
            <div class="total-banner">
                <div class="total-banner__left">
                    <div class="total-banner__label">Total Spent</div>
                    <div class="total-banner__amount">${{ ordersStore.totalSpent.toFixed(2) }}</div>
                </div>
                <div class="total-banner__stats">
                    <div class="total-stat">
                        <span class="total-stat__num">{{ ordersStore.orders.length }}</span>
                        <span class="total-stat__label">Orders</span>
                    </div>
                    <div class="total-stat">
                        <span class="total-stat__num">{{ totalItems }}</span>
                        <span class="total-stat__label">Items</span>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="orders-list">
                <TransitionGroup name="list">
                    <div v-for="order in ordersStore.orders" :key="order.id" class="order-card"
                        :class="`order-card--${order.status}`">
                        <!-- Order Header -->
                        <div class="order-card__header">
                            <div class="order-card__meta">
                                <span class="order-id">{{ order.id }}</span>
                                <span class="order-time">{{ formatTime(order.placedAt) }}</span>
                            </div>
                            <span class="status-badge" :class="`status-${order.status}`">
                                {{ statusLabel(order.status) }}
                            </span>
                        </div>

                        <!-- Status Progress Bar -->
                        <div v-if="order.status !== 'cancelled'" class="status-progress">
                            <div v-for="(step, i) in statusSteps" :key="step.key" class="status-step" :class="{
                                'done': stepIndex(order.status) > i,
                                'active': stepIndex(order.status) === i
                            }">
                                <div class="step-dot">
                                    <svg v-if="stepIndex(order.status) > i" width="10" height="10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="step-label">{{ step.label }}</div>
                                <div v-if="i < statusSteps.length - 1" class="step-line"></div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="order-items">
                            <div v-for="item in order.items" :key="item.id" class="order-item">
                                <div class="order-item__img-wrap">
                                    <img v-if="item.image" :src="item.image" :alt="item.name" class="order-item__img" />
                                    <div v-else class="order-item__img-ph">🍽️</div>
                                </div>
                                <div class="order-item__info">
                                    <span class="order-item__name">{{ item.name }}</span>
                                    <span class="order-item__qty">x{{ item.quantity }}</span>
                                </div>
                                <span class="order-item__price">${{ (item.price * item.quantity).toFixed(2) }}</span>
                            </div>
                        </div>

                        <!-- Order Footer -->
                        <div class="order-card__footer">
                            <div class="order-total">
                                <span class="order-total__label">Order Total</span>
                                <span class="order-total__amount">${{ order.total.toFixed(2) }}</span>
                            </div>
                            <button v-if="order.status === 'pending'" class="cancel-btn"
                                @click="handleCancel(order.id)">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="15" y1="9" x2="9" y2="15" />
                                    <line x1="9" y1="9" x2="15" y2="15" />
                                </svg>
                                Cancel Order
                            </button>
                            <span v-else-if="order.status === 'cancelled'" class="cancelled-note">Order cancelled</span>
                        </div>
                    </div>
                </TransitionGroup>
            </div>
        </template>

        <!-- Cancel Confirm Modal -->
        <Transition name="fade">
            <div v-if="cancelTarget" class="modal-overlay" @click.self="cancelTarget = null">
                <div class="modal">
                    <div class="modal__icon">⚠️</div>
                    <h3 class="modal__title">Cancel Order?</h3>
                    <p class="modal__body">Are you sure you want to cancel <strong>{{ cancelTarget }}</strong>? This
                        cannot be
                        undone.</p>
                    <div class="modal__actions">
                        <button class="btn btn-secondary" @click="cancelTarget = null">Keep Order</button>
                        <button class="btn btn-danger" @click="confirmCancel">Cancel Order</button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, inject } from 'vue'
import { useOrdersStore } from '@/stores/ordersStore'

const ordersStore = useOrdersStore()
const tableNumber = inject('tableNumber')
const cancelTarget = ref(null)

const statusSteps = [
    { key: 'pending', label: 'Pending' },
    { key: 'confirmed', label: 'Confirmed' },
    { key: 'preparing', label: 'Preparing' },
    { key: 'ready', label: 'Ready' },
]

const statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'cancelled']

function stepIndex(status) {
    return statusSteps.findIndex(s => s.key === status)
}

function statusLabel(status) {
    const labels = {
        pending: '⏳ Pending',
        confirmed: '✅ Confirmed',
        preparing: '👨‍🍳 Preparing',
        ready: '🔔 Ready!',
        cancelled: '✕ Cancelled',
    }
    return labels[status] || status
}

function formatTime(date) {
    if (!date) return ''
    const d = new Date(date)
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

const totalItems = computed(() =>
    ordersStore.orders
        .filter(o => o.status !== 'cancelled')
        .reduce((sum, o) => sum + o.items.reduce((s, i) => s + i.quantity, 0), 0)
)

function handleCancel(orderId) {
    cancelTarget.value = orderId
}

function confirmCancel() {
    if (cancelTarget.value) {
        ordersStore.cancelOrder(cancelTarget.value)
        cancelTarget.value = null
    }
}
</script>

<style scoped>
.orders-view {
    background: var(--surface-2);
    min-height: 100dvh;
    padding-bottom: 24px;
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

.table-chip {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--brand);
    background: var(--brand-bg);
    border: 1px solid rgba(200, 82, 42, 0.2);
    padding: 4px 10px;
    border-radius: var(--radius-full);
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

/* ─── Total Banner ──────────────────────────────────────────────────── */
.total-banner {
    background: linear-gradient(135deg, #9A3A18, #C8522A);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.total-banner__label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.75);
    font-weight: 500;
}

.total-banner__amount {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.total-banner__stats {
    display: flex;
    gap: 20px;
}

.total-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.total-stat__num {
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
}

.total-stat__label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.7);
}

/* ─── Orders List ───────────────────────────────────────────────────── */
.orders-list {
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.order-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.order-card--cancelled {
    opacity: 0.65;
}

.order-card:hover {
    box-shadow: var(--shadow-sm);
}

/* Card Header */
.order-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px 10px;
    border-bottom: 1px solid var(--border);
}

.order-card__meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.order-id {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: 0.02em;
}

.order-time {
    font-size: 0.72rem;
    color: var(--ink-muted);
}

/* Status badge */
.status-badge {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    padding: 4px 10px;
    border-radius: var(--radius-full);
}

.status-pending {
    background: var(--warning-bg);
    color: var(--warning);
}

.status-confirmed {
    background: var(--info-bg);
    color: var(--info);
}

.status-preparing {
    background: #FEF0E5;
    color: #C45E1A;
}

.status-ready {
    background: var(--success-bg);
    color: var(--success);
}

.status-cancelled {
    background: var(--surface-3);
    color: var(--ink-muted);
}

/* Progress */
.status-progress {
    display: flex;
    align-items: flex-start;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
    gap: 0;
}

.status-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    position: relative;
}

.step-dot {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid var(--border);
    background: var(--surface);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    color: var(--success);
    transition: all 0.25s;
    z-index: 1;
}

.status-step.done .step-dot {
    background: var(--success);
    border-color: var(--success);
    color: #fff;
}

.status-step.active .step-dot {
    background: var(--brand);
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200, 82, 42, 0.2);
}

.step-label {
    font-size: 0.62rem;
    font-weight: 600;
    color: var(--ink-muted);
    text-align: center;
    white-space: nowrap;
}

.status-step.active .step-label {
    color: var(--brand);
}

.status-step.done .step-label {
    color: var(--success);
}

.step-line {
    position: absolute;
    top: 9px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: var(--border);
    z-index: 0;
}

.status-step.done .step-line {
    background: var(--success);
}

/* Items */
.order-items {
    padding: 10px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.order-item__img-wrap {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--surface-3);
    flex-shrink: 0;
}

.order-item__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item__img-ph {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.order-item__info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
}

.order-item__name {
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--ink);
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.order-item__qty {
    font-size: 0.75rem;
    color: var(--ink-muted);
    font-weight: 500;
    background: var(--surface-3);
    padding: 2px 6px;
    border-radius: var(--radius-full);
    flex-shrink: 0;
}

.order-item__price {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--ink-soft);
    flex-shrink: 0;
}

/* Footer */
.order-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px 14px;
    border-top: 1px solid var(--border);
    background: var(--surface-2);
}

.order-total {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.order-total__label {
    font-size: 0.72rem;
    color: var(--ink-muted);
}

.order-total__amount {
    font-family: var(--font-display);
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--brand);
}

.cancel-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--error);
    background: var(--error-bg);
    padding: 7px 14px;
    border-radius: var(--radius-full);
    transition: all 0.15s;
}

.cancel-btn:hover {
    background: #FACBBE;
}

.cancelled-note {
    font-size: 0.78rem;
    color: var(--ink-muted);
    font-style: italic;
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
    text-align: center;
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

.modal__icon {
    font-size: 2rem;
    margin-bottom: 8px;
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
</style>