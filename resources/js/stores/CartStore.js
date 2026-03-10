import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore('cart', () => {
    const items = ref([])

    const itemCount = computed(() =>
        items.value.reduce((sum, item) => sum + item.quantity, 0)
    )

    const subtotal = computed(() =>
        items.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
    )

    function addItem(menuItem) {
        const existing = items.value.find(i => i.id === menuItem.id)
        if (existing) {
            existing.quantity++
        } else {
            items.value.push({ ...menuItem, quantity: 1 })
        }
    }

    function removeItem(itemId) {
        items.value = items.value.filter(i => i.id !== itemId)
    }

    function incrementQuantity(itemId) {
        const item = items.value.find(i => i.id === itemId)
        if (item) item.quantity++
    }

    function decrementQuantity(itemId) {
        const item = items.value.find(i => i.id === itemId)
        if (item) {
            if (item.quantity > 1) {
                item.quantity--
            } else {
                removeItem(itemId)
            }
        }
    }

    function clearCart() {
        items.value = []
    }

    function getItemQuantity(itemId) {
        const item = items.value.find(i => i.id === itemId)
        return item ? item.quantity : 0
    }

    return {
        items,
        itemCount,
        subtotal,
        addItem,
        removeItem,
        incrementQuantity,
        decrementQuantity,
        clearCart,
        getItemQuantity,
    }
})