<template>
    <div class="sticky top-[64px] z-30 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="flex gap-2 px-4 py-3 overflow-x-auto scrollbar-hide">
            <button
                class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap"
                :class="active === 'all'
                    ? 'bg-primary text-white shadow-md shadow-primary-dark/20'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" @click="$emit('select', 'all')">
                🍽️ All
            </button>

            <button v-for="cat in categories" :key="cat.id"
                class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap"
                :class="active === cat.id
                    ? 'bg-primary text-white shadow-md shadow-primary-dark/20'
                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" @click="$emit('select', cat.id)">
                {{ getCategoryEmoji(cat.name) }} {{ cat.name }}
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    categories: { type: Array, default: () => [] },
    active: { type: [String, Number], default: 'all' },
});

defineEmits(['select']);

function getCategoryEmoji(name) {
    const n = name?.toLowerCase() || '';
    if (n.includes('starter') || n.includes('appetizer')) return '🥗';
    if (n.includes('main') || n.includes('entree')) return '🍛';
    if (n.includes('pizza')) return '🍕';
    if (n.includes('burger')) return '🍔';
    if (n.includes('pasta') || n.includes('noodle')) return '🍝';
    if (n.includes('dessert') || n.includes('sweet')) return '🍰';
    if (n.includes('drink') || n.includes('beverage')) return '🥤';
    if (n.includes('breakfast')) return '🍳';
    if (n.includes('soup')) return '🍜';
    if (n.includes('seafood') || n.includes('fish')) return '🐟';
    if (n.includes('grill') || n.includes('bbq')) return '🥩';
    if (n.includes('veg') || n.includes('salad')) return '🥦';
    return '🍽️';
}
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>