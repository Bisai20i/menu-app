import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

// localStorage key scoped per table so two different tables on the
// same device (e.g. shared family phone) never bleed into each other.
const storageKey = (tUuid) => `dine_session_${tUuid}`;

// Global device identifier key — survives across tables and sessions
const DEVICE_ID_KEY = 'dine_device_id';

/**
 * Get or create a unique device identifier.
 * Persisted in localStorage so it survives page refreshes and new QR scans.
 */
function getOrCreateDeviceId() {
    try {
        let id = localStorage.getItem(DEVICE_ID_KEY);
        if (!id) {
            id = (typeof crypto !== 'undefined' && crypto.randomUUID)
                ? crypto.randomUUID()
                : 'dev-' + Date.now() + '-' + Math.random().toString(36).slice(2, 10);
            localStorage.setItem(DEVICE_ID_KEY, id);
        }
        return id;
    } catch {
        // Private browsing or storage error — generate a transient id
        return 'tmp-' + Date.now() + '-' + Math.random().toString(36).slice(2, 10);
    }
}

function readStorage(tUuid) {
    try {
        const raw = localStorage.getItem(storageKey(tUuid));
        if (!raw) return null;
        try {
            return JSON.parse(raw);
        } catch (e) {
            console.error('Failed to parse cart storage:', e);
            localStorage.removeItem(storageKey(tUuid));
            return null;
        }
    } catch {
        return null;
    }
}

function writeStorage(tUuid, data) {
    try {
        localStorage.setItem(storageKey(tUuid), JSON.stringify(data));
    } catch {
        // Storage quota exceeded or private-browsing restriction — degrade silently
    }
}

function clearStorage(tUuid) {
    try {
        localStorage.removeItem(storageKey(tUuid));
    } catch { /* silent */ }
}

export const useCartStore = defineStore('cart', () => {
    const items        = ref([]);
    const isCartOpen   = ref(false);
    const restaurantId = ref(null);
    const tableUuid    = ref(null);
    const tableNumber  = ref(null);
    const sessionUuid  = ref(null);
    const orderNote    = ref('');
    const deviceId     = ref(getOrCreateDeviceId());
    const hasPendingReconfirmation = ref(false);

    const totalItems = computed(() =>
        items.value.reduce((sum, item) => sum + item.quantity, 0)
    );

    const totalPrice = computed(() =>
        items.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
    );

    // ── Table / session setup ───────────────────────────────────────

    function setTableInfo(rId, tUuid, tNumber) {
        restaurantId.value = rId;
        tableUuid.value    = tUuid;
        tableNumber.value  = tNumber;
    }

    /**
     * Called once after setTableInfo.
     * Reads localStorage and rehydrates sessionUuid if a stored session
     * exists for this exact table UUID.
     * Returns the stored sessionUuid (or null) so the caller can decide
     * whether to fetch existing orders.
     */
    function rehydrateSession() {
        if (!tableUuid.value) return null;
        const stored = readStorage(tableUuid.value);
        if (stored?.sessionUuid) {
            sessionUuid.value = stored.sessionUuid;
            return stored.sessionUuid;
        }
        return null;
    }

    /**
     * Persist session UUID to localStorage after first successful order.
     */
    function setSessionUuid(uuid) {
        sessionUuid.value = uuid;
        if (tableUuid.value) {
            writeStorage(tableUuid.value, { sessionUuid: uuid, deviceId: deviceId.value });
        }
    }

    /**
     * Wipe the stored session for this table (called when session is closed
     * or a new QR scan on the same device opens a fresh session).
     */
    function clearSession() {
        if (tableUuid.value) clearStorage(tableUuid.value);
        sessionUuid.value = null;
    }

    // ── Cart actions ────────────────────────────────────────────────

    function addItem(menuItem) {
        const existing = items.value.find(i => i.id === menuItem.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            items.value.push({ ...menuItem, quantity: 1 });
        }
    }

    function removeItem(menuItemId) {
        const idx = items.value.findIndex(i => i.id === menuItemId);
        if (idx === -1) return;
        if (items.value[idx].quantity > 1) {
            items.value[idx].quantity -= 1;
        } else {
            items.value.splice(idx, 1);
        }
    }

    function deleteItem(menuItemId) {
        items.value = items.value.filter(i => i.id !== menuItemId);
    }

    function clearCart() {
        items.value    = [];
        orderNote.value = '';
    }

    function openCart()   { isCartOpen.value = true; }
    function closeCart()  { isCartOpen.value = false; }
    function toggleCart() { isCartOpen.value = !isCartOpen.value; }

    function getItemQuantity(menuItemId) {
        const item = items.value.find(i => i.id === menuItemId);
        return item ? item.quantity : 0;
    }

    function setReconfirmationStatus(status) {
        hasPendingReconfirmation.value = status;
    }

    return {
        items, isCartOpen, restaurantId, tableUuid, tableNumber,
        sessionUuid, orderNote, deviceId, hasPendingReconfirmation,
        totalItems, totalPrice,
        setTableInfo, rehydrateSession, setSessionUuid, clearSession,
        addItem, removeItem, deleteItem, clearCart,
        openCart, closeCart, toggleCart, getItemQuantity,
        setReconfirmationStatus,
    };
});