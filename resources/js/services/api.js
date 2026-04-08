const BASE_URL = `${window.location.origin}/api`;


async function request(endpoint, options = {}) {
    const response = await fetch(`${BASE_URL}${endpoint}`, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers,
        },
        ...options,
    });

    if (!response.ok) {
        const error = await response.json().catch(() => ({ message: 'Network error' }));
        throw new Error(error.message || `HTTP ${response.status}`);
    }

    try {
        return await response.json();
    } catch (e) {
        console.error('API response was not valid JSON:', e);
        // If it's HTML, logging may reveal why (e.g. redirect to login)
        const text = await response.text().catch(() => '');
        console.log('Response body preview:', text.substring(0, 500));
        throw new Error('Server returned invalid data format.');
    }
}

export const menuApi = {
    // GET /api/menu/{restaurant_slug}/{table_uuid}?device_id=...
    getMenu(restaurantSlug, tableUuid, deviceId) {
        const params = deviceId ? `?device_id=${encodeURIComponent(deviceId)}` : '';
        return request(`/menu/${restaurantSlug}/${tableUuid}${params}`);
    },

    // GET /api/menu/{restaurant_slug}/categories
    getCategories(restaurantSlug) {
        return request(`/menu/${restaurantSlug}/categories`);
    },

    // POST /api/waiter/call
    callWaiter(restaurantId, tableUuid) {
        return request(`/waiter/call`, {
            method: 'POST',
            body: JSON.stringify({ restaurant_id: restaurantId, table_uuid: tableUuid }),
        });
    },

    // POST /api/orders
    placeOrder(payload) {
        return request(`/orders`, {
            method: 'POST',
            body: JSON.stringify(payload),
        });
    },

    // GET /api/sessions/{session_uuid}/orders
    getSessionOrders(sessionUuid) {
        return request(`/sessions/${sessionUuid}/orders`);
    },

    // POST /api/orders/{order_uuid}/cancel
    cancelOrder(orderUuid) {
        return request(`/orders/${orderUuid}/cancel`, {
            method: 'POST',
        });
    },
    confirmOrder(orderUuid) {
        return request(`/orders/${orderUuid}/confirm`, {
            method: 'POST',
        });
    },
};

export default menuApi;