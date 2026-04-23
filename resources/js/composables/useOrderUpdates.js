import { onMounted, onUnmounted } from 'vue';
import { useCartStore } from '../stores/cart.js';
import { useToast } from './useToast.js';

/**
 * Shared state for the order update listener to prevent duplicate
 * subscriptions and double-toasts.
 */
const callbacks = new Set();
let isSubscribed = false;

function handleStatusUpdate(e) {
    const updatedOrder = e.order;
    if (!updatedOrder) return;

    // Play notification sound
    const audio = new Audio('/notification.mp3');
    audio.play().catch(() => { });

    // Toast notification
    const labels = {
        confirmed: 'Your order is being prepared!',
        served: 'Your order has been served!',
        cancelled: 'Your order was cancelled.',
    };
    const message = labels[updatedOrder.status] ?? `Order status: ${updatedOrder.status}`;
    
    // Global toast — use useToast statically here
    const { info } = useToast();
    info(message);

    // Notify all active page-level subscribers
    callbacks.forEach(cb => {
        if (typeof cb === 'function') {
            cb(updatedOrder);
        }
    });
}

/**
 * Global composable — mount once in App.vue so both MenuView and OrdersView
 * receive real-time order status updates from the WebSocket server.
 */
export function useOrderUpdates(onUpdate = null) {
    const cartStore = useCartStore();

    function subscribe() {
        if (isSubscribed || !window.Echo || !cartStore.deviceId) return;
        
        window.Echo
            .channel(`orders.${cartStore.deviceId}`)
            .listen('.OrderStatusUpdated', handleStatusUpdate);
        
        isSubscribed = true;
    }

    function unsubscribe() {
        if (!isSubscribed || !window.Echo || !cartStore.deviceId) return;
        
        // We only truly leave if NO ONE is listening anymore.
        // In this app, App.vue always keeps the listener alive.
        if (callbacks.size === 0) {
            window.Echo.leave(`orders.${cartStore.deviceId}`);
            isSubscribed = false;
        }
    }

    onMounted(() => {
        if (onUpdate) callbacks.add(onUpdate);
        subscribe();
    });

    onUnmounted(() => {
        if (onUpdate) callbacks.delete(onUpdate);
        unsubscribe();
    });

    return { subscribe, unsubscribe };
}
