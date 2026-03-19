import { onMounted, onUnmounted } from 'vue';
import { useCartStore } from '../stores/cart.js';
import { useToast } from './useToast.js';

/**
 * Global composable — mount once in App.vue so both MenuView and OrdersView
 * receive real-time order status updates from the WebSocket server.
 *
 * The backend `OrderStatusUpdated` event broadcasts on the public channel
 * `orders.{deviceId}` with the event name `OrderStatusUpdated`.
 */
export function useOrderUpdates(onUpdate = null) {
    const cartStore = useCartStore();
    const toast = useToast();

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
        toast.info(message);

        // Call any page-specific handler (e.g. OrdersView merging state)
        if (typeof onUpdate === 'function') {
            onUpdate(updatedOrder);
        }
    }

    function subscribe() {
        if (!window.Echo || !cartStore.deviceId) return;

        window.Echo
            .channel(`orders.${cartStore.deviceId}`)
            .listen('.OrderStatusUpdated', handleStatusUpdate);
    }

    function unsubscribe() {
        if (!window.Echo || !cartStore.deviceId) return;
        window.Echo.leave(`orders.${cartStore.deviceId}`);
    }

    onMounted(subscribe);
    onUnmounted(unsubscribe);

    return { subscribe, unsubscribe };
}
