<template>
    <div class="flex gap-3 bg-white rounded-2xl p-3 shadow-sm border border-gray-100 active:scale-[0.98] transition-transform cursor-pointer"
        @click="$emit('view', item)">
        <!-- Image -->
        <div
            class="shrink-0 w-24 h-24 rounded-xl overflow-hidden bg-gradient-to-br from-primary-light to-primary-light flex items-center justify-center">
            <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
            <span v-else class="text-3xl">{{ getEmoji(item) }}</span>
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
            <div>
                <div class="flex items-start gap-1.5">
                    <h3 class="font-semibold text-gray-800 text-sm leading-snug flex-1">{{ item.name }}</h3>
                    <div class="flex gap-1 shrink-0">
                        <span v-if="item.is_featured" class="text-yellow-400 text-xs">⭐</span>
                        <span v-if="!item.is_available"
                            class="text-xs bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded-full font-medium">Unavailable</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-0.5 leading-relaxed line-clamp-2 mb-2">{{ item.description }}</p>

                <!-- Dietary badges -->
                <div v-if="dietaryTags.length" class="flex flex-wrap gap-1 mt-1.5">
                    <span v-for="tag in dietaryTags.slice(0, 3)" :key="tag"
                        class="text-[10px] font-medium px-1.5 py-0.5 rounded-full" :class="getDietaryClass(tag)">{{ tag
                        }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-2">
                <span class="font-bold text-gray-800 text-sm">Rs. {{ formatPrice(item.price) }}</span>

                <!-- Quantity controls or Add button -->
                <div v-if="quantity > 0" class="flex items-center gap-2">
                    <button
                        class="w-7 h-7 rounded-full bg-primary-light text-primary-dark font-bold text-base flex items-center justify-center active:scale-90 transition-transform"
                        @click.stop="$emit('remove', item)">−</button>
                    <span class="font-bold text-gray-800 text-sm w-4 text-center">{{ quantity }}</span>
                    <button
                        class="w-7 h-7 rounded-full bg-primary text-white font-bold text-base flex items-center justify-center active:scale-90 transition-transform"
                        @click.stop="$emit('add', item)">+</button>
                </div>

                <button v-else :disabled="!item.is_available"
                    class="flex items-center gap-1 bg-primary text-white text-xs font-semibold px-3 py-1.5 rounded-full active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed hover:bg-primary-dark"
                    @click.stop="$emit('add', item)">
                    <span class="text-sm leading-none">+</span> Add
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    item: { type: Object, required: true },
    quantity: { type: Number, default: 0 },
});

defineEmits(['add', 'remove', 'view']);

const dietaryTags = computed(() => {
    if (!props.item.dietary_info) return [];
    if (Array.isArray(props.item.dietary_info)) return props.item.dietary_info;
    try { return JSON.parse(props.item.dietary_info); } catch { return []; }
});

function formatPrice(price) {
    return Number(price).toFixed(2);
}

function getEmoji(item) {
    const name = item.name?.toLowerCase() || '';
    if (name.includes('pizza')) return '🍕';
    if (name.includes('burger')) return '🍔';
    if (name.includes('pasta') || name.includes('noodle')) return '🍝';
    if (name.includes('salad')) return '🥗';
    if (name.includes('soup')) return '🍜';
    if (name.includes('chicken')) return '🍗';
    if (name.includes('fish') || name.includes('seafood')) return '🐟';
    if (name.includes('dessert') || name.includes('cake') || name.includes('sweet')) return '🍰';
    if (name.includes('drink') || name.includes('juice') || name.includes('coffee')) return '☕';
    if (name.includes('rice') || name.includes('biryani')) return '🍚';
    if (name.includes('bread') || name.includes('naan') || name.includes('roti')) return '🫓';
    return '🍽️';
}

function getDietaryClass(tag) {
    const t = tag.toLowerCase();
    if (t.includes('vegan')) return 'bg-green-100 text-green-700';
    if (t.includes('vegetarian')) return 'bg-lime-100 text-lime-700';
    if (t.includes('gluten')) return 'bg-amber-100 text-amber-700';
    if (t.includes('spicy')) return 'bg-red-100 text-red-600';
    if (t.includes('halal')) return 'bg-emerald-100 text-emerald-700';
    if (t.includes('dairy')) return 'bg-blue-100 text-blue-600';
    return 'bg-gray-100 text-gray-600';
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>