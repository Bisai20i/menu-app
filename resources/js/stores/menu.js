import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { menuApi } from '../services/api.js';

export const useMenuStore = defineStore('menu', () => {
    const restaurant = ref(null);
    const categories = ref([]);
    const featuredItems = ref([]);
    const allItems = ref([]);
    const isLoading = ref(false);
    const error = ref(null);

    const activeCategory = ref('all');
    const searchQuery = ref('');

    const filteredItems = computed(() => {
        let items = activeCategory.value === 'all'
            ? allItems.value
            : allItems.value.filter(i => i.menu_category_id === activeCategory.value);

        if (searchQuery.value.trim()) {
            const q = searchQuery.value.toLowerCase();
            items = items.filter(i =>
                i.name.toLowerCase().includes(q) ||
                (i.description && i.description.toLowerCase().includes(q))
            );
        }

        return items;
    });

    const groupedByCategory = computed(() => {
        if (searchQuery.value.trim()) {
            return [{ id: 'search', name: 'Search Results', items: filteredItems.value }];
        }
        if (activeCategory.value !== 'all') {
            const cat = categories.value.find(c => c.id === activeCategory.value);
            return cat ? [{ ...cat, items: filteredItems.value }] : [];
        }
        return categories.value.map(cat => ({
            ...cat,
            items: allItems.value.filter(i => i.menu_category_id === cat.id && i.is_available),
        })).filter(c => c.items.length > 0);
    });

    async function loadMenu(restaurantSlug, tableUuid) {
        isLoading.value = true;
        error.value = null;
        try {
            const data = await menuApi.getMenu(restaurantSlug, tableUuid);
            restaurant.value = data.restaurant;
            categories.value = data.categories || [];
            allItems.value = data.items || [];
            featuredItems.value = (data.items || []).filter(i => i.is_featured && i.is_available);
        } catch (err) {
            error.value = err.message;
        } finally {
            isLoading.value = false;
        }
    }

    function setCategory(categoryId) {
        activeCategory.value = categoryId;
        searchQuery.value = '';
    }

    function setSearch(query) {
        searchQuery.value = query;
        activeCategory.value = 'all';
    }

    return {
        restaurant, categories, featuredItems, allItems,
        isLoading, error, activeCategory, searchQuery,
        filteredItems, groupedByCategory,
        loadMenu, setCategory, setSearch,
    };
});