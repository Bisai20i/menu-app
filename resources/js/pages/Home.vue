<script setup>
import { ref } from 'vue';

// A simple reactive variable to test if Vue is working
const counter = ref(0);

const increment = () => {
    counter.value++;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6 text-center">
        <!-- Icon/Logo Placeholder -->
        <div class="w-20 h-20 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>

        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
            Vue SPA is Running!
        </h1>
        

        <!-- Reactivity Test -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 w-full max-w-xs">
            <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Reactivity Test</p>
            <div class="text-5xl font-bold text-gray-800 mb-4">{{ counter }}</div>
            <button 
                @click="increment"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
            >
                Click to Count
            </button>
        </div>

        <!-- Navigation Link -->
        <div class="mt-8">
            <router-link to="/cart" class="text-red-600 hover:underline font-medium">
                Go to Cart &rarr;
            </router-link>
        </div>
    </div>
</template>