import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useOrdersStore = defineStore('orders', () => {
    const orders = ref([])
    let orderCounter = 1

    const totalSpent = computed(() =>
        orders.value
            .filter(o => o.status !== 'cancelled')
            .reduce((sum, o) => sum + o.total, 0)
    )

    const activeOrders = computed(() =>
        orders.value.filter(o => o.status !== 'cancelled')
    )

    function placeOrder(cartItems, tableNumber, subtotal) {
        const orderId = `ORD-${String(orderCounter++).padStart(3, '0')}`
        const order = {
            id: orderId,
            tableNumber,
            items: cartItems.map(item => ({ ...item })),
            total: subtotal,
            status: 'pending',
            placedAt: new Date(),
        }
        orders.value.unshift(order)
        return orderId
    }

    function cancelOrder(orderId) {
        const order = orders.value.find(o => o.id === orderId)
        if (order && order.status === 'pending') {
            order.status = 'cancelled'
            return true
        }
        return false
    }

    // Simulate status progression for demo
    function simulateStatusUpdate(orderId) {
        const statusFlow = ['pending', 'confirmed', 'preparing', 'ready']
        const order = orders.value.find(o => o.id === orderId)
        if (!order) return

        const currentIndex = statusFlow.indexOf(order.status)
        if (currentIndex < statusFlow.length - 1) {
            setTimeout(() => {
                const o = orders.value.find(o => o.id === orderId)
                if (o && o.status !== 'cancelled') {
                    o.status = statusFlow[currentIndex + 1]
                    simulateStatusUpdate(orderId)
                }
            }, 15000 + Math.random() * 10000)
        }
    }

    return {
        orders,
        totalSpent,
        activeOrders,
        placeOrder,
        cancelOrder,
        simulateStatusUpdate,
    }
})