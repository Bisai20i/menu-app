<template>
    <section class="featured-section">
        <h2 class="featured-title">
            <span class="star-icon">⭐</span> Featured
        </h2>
        <div class="carousel" ref="scrollRef">
            <div v-for="item in items" :key="item.id" class="featured-card">
                <div class="featured-card__img-wrap">
                    <img :src="item.image" :alt="item.name" class="featured-card__img" loading="lazy" />
                    <div class="featured-card__overlay">
                        <div class="featured-card__tags">
                            <span v-for="tag in item.tags?.slice(0, 1)" :key="tag" class="feat-tag">{{ tag }}</span>
                        </div>
                    </div>
                </div>
                <div class="featured-card__body">
                    <div>
                        <div class="featured-card__name">{{ item.name }}</div>
                        <div class="featured-card__desc">{{ item.description }}</div>
                    </div>
                    <div class="featured-card__footer">
                        <span class="featured-card__price">${{ item.price.toFixed(2) }}</span>
                        <div v-if="getQty(item.id) > 0" class="qty-stepper">
                            <button class="qty-btn" @click="decrement(item)">−</button>
                            <span class="qty-count">{{ getQty(item.id) }}</span>
                            <button class="qty-btn" @click="increment(item)">+</button>
                        </div>
                        <button v-else class="feat-add-btn" @click="increment(item)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { useCartStore } from '@/stores/cartStore'

defineProps({ items: Array })

const cartStore = useCartStore()

function getQty(id) { return cartStore.getItemQuantity(id) }
function increment(item) { cartStore.addItem(item) }
function decrement(item) { cartStore.decrementQuantity(item.id) }
</script>

<style scoped>
.featured-section {
    padding: 0 0 4px;
}

.featured-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ink);
    padding: 0 16px 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.star-icon {
    font-size: 0.9rem;
}

.carousel {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding: 4px 16px 12px;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.carousel::-webkit-scrollbar {
    display: none;
}

.featured-card {
    min-width: 200px;
    max-width: 200px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    scroll-snap-align: start;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}

.featured-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.featured-card__img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: var(--surface-3);
}

.featured-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.featured-card:hover .featured-card__img {
    transform: scale(1.06);
}

.featured-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(26, 18, 8, 0.4));
}

.featured-card__tags {
    position: absolute;
    bottom: 8px;
    left: 8px;
    display: flex;
    gap: 4px;
}

.feat-tag {
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    padding: 2px 7px;
    border-radius: var(--radius-full);
    background: var(--brand);
    color: #fff;
}

.featured-card__body {
    padding: 10px 12px 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
    justify-content: space-between;
}

.featured-card__name {
    font-family: var(--font-display);
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--ink);
    line-height: 1.3;
}

.featured-card__desc {
    font-size: 0.72rem;
    color: var(--ink-muted);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-top: 2px;
}

.featured-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 4px;
}

.featured-card__price {
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--brand);
    font-family: var(--font-display);
}

.feat-add-btn {
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: var(--radius-full);
    background: var(--brand);
    color: #fff;
    transition: background 0.15s;
}

.feat-add-btn:hover {
    background: var(--brand-dark);
}

.qty-stepper {
    height: 30px;
}

.qty-btn {
    width: 30px;
    height: 30px;
    font-size: 1rem;
}

.qty-count {
    font-size: 0.82rem;
    min-width: 24px;
}
</style>