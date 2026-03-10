<template>
    <div class="menu-card" :class="{ 'menu-card--featured': featured }">
        <div class="menu-card__img-wrap">
            <img v-if="item.image" :src="item.image" :alt="item.name" class="menu-card__img" loading="lazy"
                @error="imgError = true" />
            <div v-else class="menu-card__img-placeholder">🍽️</div>
            <div v-if="item.tags?.length" class="menu-card__tags">
                <span v-for="tag in item.tags.slice(0, 2)" :key="tag" class="tag">{{ tag }}</span>
            </div>
        </div>

        <div class="menu-card__body">
            <div class="menu-card__info">
                <h3 class="menu-card__name">{{ item.name }}</h3>
                <p class="menu-card__desc">{{ item.description }}</p>
                <div class="menu-card__footer">
                    <span class="menu-card__price">${{ item.price.toFixed(2) }}</span>
                    <div class="menu-card__action">
                        <div v-if="qty > 0" class="qty-stepper">
                            <button class="qty-btn" @click.stop="decrement" aria-label="Decrease">−</button>
                            <span class="qty-count">{{ qty }}</span>
                            <button class="qty-btn" @click.stop="increment" aria-label="Increase">+</button>
                        </div>
                        <button v-else class="add-btn" @click.stop="increment" :disabled="!item.available">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useCartStore } from '@/stores/cartStore'

const props = defineProps({
    item: { type: Object, required: true },
    featured: { type: Boolean, default: false },
})

const cartStore = useCartStore()
const imgError = ref(false)

const qty = computed(() => cartStore.getItemQuantity(props.item.id))

function increment() {
    cartStore.addItem(props.item)
}
function decrement() {
    cartStore.decrementQuantity(props.item.id)
}
</script>

<style scoped>
.menu-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}

.menu-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.menu-card--featured {
    border-color: rgba(200, 82, 42, 0.25);
    box-shadow: 0 2px 8px rgba(200, 82, 42, 0.08);
}

/* Image */
.menu-card__img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
    background: var(--surface-3);
    flex-shrink: 0;
}

.menu-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.menu-card:hover .menu-card__img {
    transform: scale(1.04);
}

.menu-card__img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--ink-faint);
}

.menu-card__tags {
    position: absolute;
    top: 8px;
    left: 8px;
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.tag {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    padding: 3px 7px;
    border-radius: var(--radius-full);
    background: rgba(26, 18, 8, 0.65);
    color: #fff;
    backdrop-filter: blur(4px);
}

/* Body */
.menu-card__body {
    padding: 12px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.menu-card__info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}

.menu-card__name {
    font-family: var(--font-display);
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--ink);
    line-height: 1.3;
}

.menu-card__desc {
    font-size: 0.76rem;
    color: var(--ink-muted);
    line-height: 1.45;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.menu-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
}

.menu-card__price {
    font-size: 1rem;
    font-weight: 700;
    color: var(--brand);
    font-family: var(--font-display);
}

/* Add button */
.add-btn {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: var(--radius-full);
    background: var(--brand);
    color: #fff;
    transition: all 0.15s;
    box-shadow: 0 2px 8px rgba(200, 82, 42, 0.22);
}

.add-btn:hover {
    background: var(--brand-dark);
    transform: translateY(-1px);
}

.add-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Qty stepper override for card */
.qty-stepper {
    height: 34px;
}
</style>