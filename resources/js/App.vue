<template>
    <div class="antialiased">
        <RouterView />
    </div>
</template>

<script setup>
    import { RouterView } from 'vue-router';
    import { useOrderUpdates } from './composables/useOrderUpdates.js';

    // Global real-time listener — fires on any page (MenuView, OrdersView, etc.)
    useOrderUpdates();
</script>