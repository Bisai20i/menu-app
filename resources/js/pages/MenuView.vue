<template>
    <div class="menu-view">
        <!-- Hero Banner -->
        <section class="hero">
            <div class="hero__bg"></div>
            <div class="hero__content">
                <h1 class="hero__title">{{ restaurant.name }}</h1>
                <p class="hero__tagline">{{ restaurant.tagline }}</p>
                <div class="hero__meta">
                    <span class="hero__meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ restaurant.openHours }}
                    </span>
                    <span class="hero__divider">•</span>
                    <span class="hero__meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        {{ restaurant.address }}
                    </span>
                </div>
            </div>
        </section>

        <!-- Search Bar -->
        <div class="search-wrap">
            <div class="search-box">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input v-model="searchQuery" class="search-input" type="text" placeholder="Search menu items..."
                    autocomplete="off" spellcheck="false" />
                <button v-if="searchQuery" class="search-clear" @click="searchQuery = ''" aria-label="Clear search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Search Results -->
        <div v-if="searchQuery.trim()" class="search-results">
            <div class="section-header">
                <span class="section-title">{{ filteredItems.length }} result{{ filteredItems.length !== 1 ? 's' : '' }}
                    for "{{ searchQuery }}"</span>
            </div>
            <div v-if="filteredItems.length" class="menu-grid">
                <MenuItemCard v-for="item in filteredItems" :key="item.id" :item="item" />
            </div>
            <div v-else class="empty-state">
                <div class="emoji">🔍</div>
                <h3>No items found</h3>
                <p>Try searching for something else like "burger" or "pasta"</p>
            </div>
        </div>

        <!-- Normal Menu View -->
        <template v-else>
            <!-- Featured Carousel -->
            <FeaturedCarousel :items="featuredItems" />

            <!-- Category Tabs -->
            <CategoryTabs :categories="allCategories" v-model="activeCategory" />

            <!-- Menu Items by Category -->
            <div class="menu-body">
                <template v-for="cat in allCategories" :key="cat.id">
                    <section v-if="getItemsByCategory(cat.id).length" :id="`cat-${cat.id}`" class="menu-section"
                        ref="sectionRefs">
                        <div class="section-header">
                            <span class="section-title">{{ cat.icon }} {{ cat.label.replace(/^⭐\s*/, '') }}</span>
                            <span class="section-count">{{ getItemsByCategory(cat.id).length }} items</span>
                        </div>
                        <div class="menu-grid">
                            <MenuItemCard v-for="item in getItemsByCategory(cat.id)" :key="item.id" :item="item"
                                :featured="cat.id === 'featured'" />
                        </div>
                    </section>
                </template>
            </div>
        </template>

        <!-- Floating Cart CTA -->
        <transition name="slide-up">
            <div v-if="cartStore.itemCount > 0 && !searchQuery" class="cart-cta">
                <router-link to="/cart" class="cart-cta__btn">
                    <div class="cart-cta__left">
                        <span class="cart-cta__count">{{ cartStore.itemCount }}</span>
                        <span>View Cart</span>
                    </div>
                    <span class="cart-cta__total">${{ cartStore.subtotal.toFixed(2) }}</span>
                </router-link>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import MenuItemCard from '@/components/MenuItemCard.vue'
import FeaturedCarousel from '@/components/FeaturedCarousel.vue'
import CategoryTabs from '@/components/CategoryTabs.vue'
import { menuItems, featuredItems, categories, restaurant } from '@/data/menuData'
import { useCartStore } from '@/stores/cartStore'

const cartStore = useCartStore()
const searchQuery = ref('')
const activeCategory = ref('starters')

// Only show non-featured categories in tab list (featured shown as carousel)
const allCategories = computed(() =>
    categories.filter(c => c.id !== 'featured')
)

function getItemsByCategory(catId) {
    if (catId === 'featured') return featuredItems
    return menuItems.filter(i => i.category === catId)
}

const filteredItems = computed(() => {
    const q = searchQuery.value.trim().toLowerCase()
    if (!q) return []
    return menuItems.filter(item =>
        item.name.toLowerCase().includes(q) ||
        item.description.toLowerCase().includes(q) ||
        item.category.toLowerCase().includes(q) ||
        item.tags?.some(t => t.toLowerCase().includes(q))
    )
})
</script>

<style scoped>
.menu-view {
    background: var(--surface);
}

/* ─── Hero ─────────────────────────────────────────────────────────── */
.hero {
    position: relative;
    overflow: hidden;
    padding: 28px 20px 24px;
    background: var(--brand);
}

.hero__bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #9A3A18 0%, #C8522A 45%, #E07038 100%);
}

.hero__bg::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.hero__content {
    position: relative;
    z-index: 1;
}

.hero__title {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
    margin-bottom: 4px;
}

.hero__tagline {
    font-size: 0.88rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 300;
    margin-bottom: 12px;
}

.hero__meta {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.hero__meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.75);
}

.hero__divider {
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.7rem;
}

/* ─── Search ────────────────────────────────────────────────────────── */
.search-wrap {
    padding: 14px 16px 10px;
    background: var(--surface);
    position: sticky;
    top: calc(var(--nav-height) + 1px);
    z-index: 49;
    border-bottom: 1px solid var(--border);
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-full);
    padding: 0 14px;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.search-box:focus-within {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200, 82, 42, 0.1);
}

.search-icon {
    color: var(--ink-muted);
    flex-shrink: 0;
}

.search-input {
    flex: 1;
    padding: 11px 0;
    font-size: 0.9rem;
    color: var(--ink);
    background: none;
    border: none;
    outline: none;
}

.search-input::placeholder {
    color: var(--ink-faint);
}

.search-clear {
    color: var(--ink-muted);
    display: flex;
    align-items: center;
    padding: 4px;
    border-radius: 50%;
    transition: background 0.1s, color 0.1s;
}

.search-clear:hover {
    background: var(--surface-3);
    color: var(--ink);
}

/* ─── Search Results ────────────────────────────────────────────────── */
.search-results {
    padding: 0 16px 16px;
}

/* ─── Section Headers ──────────────────────────────────────────────── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0 10px;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
}

.section-count {
    font-size: 0.75rem;
    color: var(--ink-muted);
    font-weight: 500;
}

/* ─── Menu Body ────────────────────────────────────────────────────── */
.menu-body {
    padding: 0 16px;
}

.menu-section {
    padding-bottom: 8px;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

/* ─── Cart CTA ─────────────────────────────────────────────────────── */
.cart-cta {
    position: fixed;
    bottom: calc(var(--bottom-nav-height) + 12px);
    left: 50%;
    transform: translateX(-50%);
    width: calc(min(100vw, var(--max-width)) - 32px);
    z-index: 90;
}

.cart-cta__btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--brand);
    color: #fff;
    padding: 14px 20px;
    border-radius: var(--radius-xl);
    box-shadow: 0 8px 24px rgba(200, 82, 42, 0.38);
    font-weight: 600;
    font-size: 0.9rem;
    transition: background 0.15s, transform 0.15s;
}

.cart-cta__btn:hover {
    background: var(--brand-dark);
    transform: translateY(-1px);
}

.cart-cta__left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-cta__count {
    min-width: 22px;
    height: 22px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
}

.cart-cta__total {
    font-size: 1rem;
    font-weight: 700;
    font-family: var(--font-display);
}
</style>