<template>
    <div class="category-tabs-wrap" ref="wrapRef">
        <div class="category-tabs" ref="tabsRef">
            <button v-for="cat in categories" :key="cat.id" class="cat-tab" :class="{ active: modelValue === cat.id }"
                @click="emit('update:modelValue', cat.id)">
                <span class="cat-icon">{{ cat.icon }}</span>
                <span class="cat-label">{{ cat.label.replace(/^⭐\s*/, '') }}</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { watch, ref, nextTick } from 'vue'

const props = defineProps({
    categories: Array,
    modelValue: String,
})
const emit = defineEmits(['update:modelValue'])

const tabsRef = ref(null)

watch(() => props.modelValue, async (newVal) => {
    await nextTick()
    if (!tabsRef.value) return
    const active = tabsRef.value.querySelector('.cat-tab.active')
    if (active) {
        active.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' })
    }
})
</script>

<style scoped>
.category-tabs-wrap {
    position: sticky;
    top: var(--nav-height);
    z-index: 50;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid var(--border);
}

.category-tabs {
    display: flex;
    gap: 4px;
    overflow-x: auto;
    padding: 10px 12px;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.category-tabs::-webkit-scrollbar {
    display: none;
}

.cat-tab {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--ink-muted);
    background: var(--surface-2);
    border: 1px solid transparent;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.15s ease;
    scroll-snap-align: start;
}

.cat-tab:hover {
    color: var(--ink);
    background: var(--surface-3);
}

.cat-tab.active {
    background: var(--brand);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 8px rgba(200, 82, 42, 0.25);
}

.cat-icon {
    font-size: 0.9rem;
}

.cat-label {
    font-weight: 600;
}
</style>