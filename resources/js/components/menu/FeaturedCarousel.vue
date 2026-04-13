<template>
    <section v-if="items.length" class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="text-xl">⭐</span> Chef's Picks
            </h2>
            <span class="text-xs font-semibold text-primary bg-primary-light/50 px-2.5 py-1 rounded-full">Featured</span>
        </div>

        <div class="relative overflow-hidden">
            <div ref="trackRef" class="flex gap-3 px-4 overflow-x-auto scrollbar-hide snap-x snap-mandatory pb-2"
                style="scroll-behavior: smooth;">
                <div v-for="item in items" :key="item.id"
                    class="snap-start shrink-0 grow-1 min-w-52 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer active:scale-95 transition-transform"
                    @click="$emit('view-item', item)">
                    <div class="relative h-32 bg-gradient-to-br from-primary-light/50 to-primary-light/30">
                        <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-4xl">
                            {{ getEmoji(item) }}
                        </div>
                        <div
                            class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-primary-dark text-xs font-bold px-2 py-0.5 rounded-full shadow-sm">
                            Featured
                        </div>
                    </div>

                    <div class="p-3">
                        <p class="font-semibold text-gray-800 text-sm leading-tight line-clamp-1">{{ item.name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">{{ item.description }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="font-bold text-primary text-sm">Rs. {{ formatPrice(item.price) }}</span>
                            <button
                                class="bg-primary text-white text-xs font-semibold px-3 py-1.5 rounded-full active:scale-95 transition-transform hover:bg-primary-dark"
                                @click.stop="$emit('add-to-cart', item)">
                                + Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator dots -->
            <div v-if="items.length > 2" class="flex justify-center gap-1.5 mt-2">
                <div v-for="(item, i) in items" :key="i" class="h-1.5 rounded-full transition-all duration-300"
                    :class="i === activeIndex ? 'w-4 bg-primary' : 'w-1.5 bg-gray-200'" />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    items: { type: Array, default: () => [] },
});

defineEmits(['add-to-cart', 'view-item']);

const trackRef = ref(null);
const activeIndex = ref(0);

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
    return '🍽️';
}

function onScroll() {
    if (!trackRef.value) return;
    const { scrollLeft, clientWidth } = trackRef.value;
    activeIndex.value = Math.round(scrollLeft / (clientWidth * 0.6));
}

onMounted(() => {
    trackRef.value?.addEventListener('scroll', onScroll, { passive: true });
});
onUnmounted(() => {
    trackRef.value?.removeEventListener('scroll', onScroll);
});
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>