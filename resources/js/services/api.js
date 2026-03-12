const BASE_URL = 'http://127.0.0.1:8000/api';
// const BASE_URL = 'http://192.168.1.120:8000/api';


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

    return response.json();
}

export const menuApi = {
    // GET /api/menu/{restaurant_slug}/{table_uuid}
    getMenu(restaurantSlug, tableUuid) {
        return request(`/menu/${restaurantSlug}/${tableUuid}`);
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
};

export default menuApi;