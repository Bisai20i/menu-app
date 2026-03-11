<template>
    <Teleport to="body">
        <div
            class="fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col gap-2 w-[90vw] max-w-sm pointer-events-none">
            <TransitionGroup name="toast">
                <div v-for="toast in toasts" :key="toast.id"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl shadow-lg pointer-events-auto text-sm font-medium"
                    :class="{
                        'bg-emerald-500 text-white': toast.type === 'success',
                        'bg-red-500 text-white': toast.type === 'error',
                        'bg-amber-400 text-amber-900': toast.type === 'info',
                    }">
                    <span v-if="toast.type === 'success'" class="text-base">✓</span>
                    <span v-else-if="toast.type === 'error'" class="text-base">✕</span>
                    <span v-else class="text-base">ℹ</span>
                    {{ toast.message }}
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup>
import { useToast } from '../../composables/useToast.js';
const { toasts } = useToast();
</script>

<style scoped>
.toast-enter-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.toast-leave-active {
    transition: all 0.2s ease-in;
}

.toast-enter-from {
    opacity: 0;
    transform: translateY(-12px) scale(0.9);
}

.toast-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.95);
}
</style>