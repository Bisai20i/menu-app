<template>
    <div v-if="show && reviewCount < 3" class="fixed right-4 z-30" :style="{ bottom: positionBottom }">
        <button 
            @click="openModal"
            class="bg-white text-primary border-2 border-primary font-bold px-4 py-3 rounded-2xl hover:bg-primary-light transition-all shadow-lg flex items-center gap-2 active:scale-95"
        >
            <span class="text-lg">⭐</span>
            <span>Give a review</span>
        </button>

        <!-- Bottom Sheet Overlay -->
        <Transition name="fade">
            <div v-if="isOpen" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50" @click="isOpen = false" />
        </Transition>

        <!-- Bottom Sheet Content -->
        <Transition name="slide-up">
            <div v-if="isOpen" class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl z-[60] shadow-2xl safe-p-bottom">
                <div class="px-6 pt-4 pb-8 max-w-screen-sm mx-auto min-h-[300px] flex flex-col">
                    <!-- Handle bar -->
                    <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6" @click="isOpen = false" />

                    <!-- Already Reviewed Screen -->
                    <div v-if="reviewCount > 0 && !isOverridden" class="flex-1 flex flex-col items-center justify-center text-center py-6">
                        <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center text-3xl mb-4">
                            ✍️
                        </div>
                        <h2 class="text-xl font-black text-gray-900 mb-2">You've already given a review</h2>
                        <p class="text-gray-500 text-sm mb-8">We appreciate your feedback! You can submit up to 3 reviews for your visit.</p>
                        
                        <button 
                            @click="isOverridden = true"
                            class="w-full bg-white text-primary border-2 border-primary font-bold py-4 rounded-2xl text-base hover:bg-primary-light transition-all animate-bounce-subtle"
                        >
                            Review Again
                        </button>
                    </div>

                    <!-- Rating Form -->
                    <template v-else>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-black text-gray-900">How was your experience?</h2>
                            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600 font-bold text-xl">✕</button>
                        </div>

                        <p class="text-gray-500 text-sm mb-6">Your feedback helps us improve and serves others in the community!</p>

                        <!-- Star Rating -->
                        <div class="flex items-center justify-center gap-3 mb-8">
                            <button 
                                v-for="star in 5" 
                                :key="star"
                                @click="rating = star"
                                class="text-4xl transition-all duration-200 transform hover:scale-110 active:scale-90"
                                :class="rating >= star ? 'grayscale-0' : 'grayscale opacity-30'"
                            >
                                ⭐
                            </button>
                        </div>

                        <!-- Review message -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Write a review (optional)</label>
                            <textarea 
                                v-model="comment"
                                rows="4"
                                class="w-full bg-gray-50 rounded-2xl p-4 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-light/50 focus:bg-white transition-all border border-gray-100"
                                placeholder="Tell us what you liked (or what we could do better)..."
                            ></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            @click="submitReview"
                            :disabled="!rating || isSubmitting"
                            class="w-full bg-primary text-white font-bold py-4 rounded-2xl text-base hover:bg-primary-dark active:scale-[0.98] transition-all shadow-lg shadow-orange-200 disabled:opacity-50 disabled:scale-100 flex items-center justify-center gap-2"
                        >
                            <span v-if="isSubmitting" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                            <span>{{ isSubmitting ? 'Submit Review' : 'Submit Review' }}</span>
                        </button>
                    </template>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useMenuStore } from '../../stores/menu.js';
import { useToast } from '../../composables/useToast.js';
import { menuApi } from '../../services/api.js';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    offsetY: {
        type: Number,
        default: 0
    }
});

const menuStore = useMenuStore();
const toast = useToast();

const isOpen = ref(false);
const rating = ref(0);
const comment = ref('');
const isSubmitting = ref(false);
const isOverridden = ref(false);
const reviewCount = ref(0);

const storageKey = computed(() => {
    return menuStore.restaurant?.id ? `restaurant_review_count_${menuStore.restaurant.id}` : null;
});

function loadReviewCount() {
    if (storageKey.value) {
        const stored = localStorage.getItem(storageKey.value);
        reviewCount.value = stored ? parseInt(stored) : 0;
    }
}

function openModal() {
    isOverridden.value = false;
    isOpen.value = true;
}

watch(storageKey, (newKey) => {
    if (newKey) loadReviewCount();
}, { immediate: true });

const positionBottom = computed(() => {
    const base = 5; // rem
    const extra = props.offsetY ? 4 : 0; // rem
    return `calc(${base + extra}rem + env(safe-area-inset-bottom))`;
});

async function submitReview() {
    if (!rating.value || !menuStore.restaurant) return;
    
    isSubmitting.value = true;
    
    const restaurantId = menuStore.restaurant.id;
    const googleLink = menuStore.restaurant.google_review_link;
    const shouldRedirect = (rating.value >= 4 && googleLink);

    try {
        await menuApi.submitReview({
            restaurant_id: restaurantId,
            rating: rating.value,
            comment: comment.value,
            redirected_to_google: !!shouldRedirect
        });

        // Increment count
        reviewCount.value++;
        if (storageKey.value) {
            localStorage.setItem(storageKey.value, reviewCount.value.toString());
        }

        toast.success('Thank you for your feedback! ❤️');
        
        if (shouldRedirect) {
            setTimeout(() => {
                window.location.href = googleLink;
            }, 1000);
        }

        isOpen.value = false;
        rating.value = 0;
        comment.value = '';
        isOverridden.value = false;
    } catch (err) {
        toast.error('Failed to submit review. Please try again.');
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<style scoped>
.safe-p-bottom {
    padding-bottom: env(safe-area-inset-bottom);
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.slide-up-enter-active, .slide-up-leave-active {
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-up-enter-from, .slide-up-leave-to {
    transform: translateY(100%);
}
</style>
