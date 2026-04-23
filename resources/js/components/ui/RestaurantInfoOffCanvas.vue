<template>
    <Transition name="slide-rtl">
        <div v-if="isOpen" class="fixed inset-0 z-[60] overflow-hidden">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="close"></div>

            <!-- Panel -->
            <div class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-2xl flex flex-col">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h2 class="text-xl font-bold text-gray-900">Restaurant Info</h2>
                    <button @click="close" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <!-- Restaurant Branding Section -->
                    <div class="px-6 py-8 flex flex-col items-center text-center bg-gradient-to-b from-gray-50 to-white">
                        <div v-if="restaurant.logo_url" class="w-24 h-24 rounded-3xl shadow-xl p-1 bg-white mb-4">
                            <img :src="restaurant.logo_url" :alt="restaurant.name" class="w-full h-full object-contain rounded-2xl" />
                        </div>
                        <div v-else class="w-24 h-24 rounded-3xl bg-primary/10 flex items-center justify-center text-4xl mb-4 shadow-inner">
                            🍽️
                        </div>
                        
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ restaurant.name }}</h3>
                        <p v-if="restaurant.description" class="mt-3 text-gray-500 text-sm leading-relaxed mx-auto">
                            {{ restaurant.description }}
                        </p>
                    </div>

                    <!-- Contact & Location Info -->
                    <div class="px-6 py-6 space-y-5">
                        <div v-if="restaurant.address" class="flex gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary shrink-0">
                                📍
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Address</p>
                                <p class="text-sm text-gray-700 font-medium leading-normal">{{ restaurant.address }}</p>
                            </div>
                        </div>

                        <div v-if="restaurant.phone" class="flex gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary shrink-0">
                                📞
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Phone</p>
                                <a :href="'tel:' + restaurant.phone" class="text-sm text-primary font-bold hover:underline">
                                    {{ restaurant.phone }}
                                </a>
                            </div>
                        </div>

                        <div v-if="restaurant.email" class="flex gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary shrink-0">
                                ✉️
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Email</p>
                                <a :href="'mailto:' + restaurant.email" class="text-sm text-primary font-bold hover:underline">
                                    {{ restaurant.email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="px-6 py-6 border-t border-gray-100 pb-20">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-bold text-gray-900">What Customers Say</h4>
                            <div v-if="restaurant.reviews?.length" class="flex items-center gap-1 bg-yellow-100 text-yellow-700 px-2 py-1 rounded-lg text-xs font-bold">
                                ⭐ {{averageRating}}
                            </div>
                        </div>

                        <div v-if="!restaurant.reviews?.length" class="text-center py-10 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                            <span class="text-3xl mb-2 block">📝</span>
                            <p class="text-gray-400 text-sm">No public reviews yet</p>
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="review in restaurant.reviews" :key="review.id" class="p-4 rounded-2xl border border-gray-100 hover:border-primary/20 transition-colors bg-white shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex gap-0.5">
                                        <span v-for="i in 5" :key="i" class="text-sm" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-200'">
                                            ★
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter">
                                        {{ formatDate(review.created_at) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 italic leading-relaxed">
                                    "{{ review.comment }}"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer (External Review Link) -->
                <div v-if="restaurant.google_review_link" class="p-6 border-t border-gray-100 bg-white sticky bottom-0">
                    <a :href="restaurant.google_review_link" target="_blank" class="w-full flex items-center justify-center gap-2 bg-gray-900 text-white font-bold py-4 rounded-2xl hover:bg-black transition-all active:scale-95 shadow-lg">
                        <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" class="w-5 h-5" />
                        Review us on Google
                    </a>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    isOpen: Boolean,
    restaurant: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close']);

function close() {
    emit('close');
}

const averageRating = computed(() => {
    if (!props.restaurant.reviews?.length) return 0;
    const total = props.restaurant.reviews.reduce((acc, r) => acc + r.rating, 0);
    return (total / props.restaurant.reviews.length).toFixed(1);
});

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}
</script>

<style scoped>
.slide-rtl-enter-active,
.slide-rtl-leave-active {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-rtl-enter-from,
.slide-rtl-leave-to {
    opacity: 0;
}

.slide-rtl-enter-from .absolute.inset-y-0.right-0 {
    transform: translateX(100%);
}

.slide-rtl-leave-to .absolute.inset-y-0.right-0 {
    transform: translateX(100%);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}
</style>
