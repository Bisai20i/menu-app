<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scroll to sections
    function scrollTo(id) {
        const el = document.getElementById(id);
        if (el) {
            window.scrollTo({
                top: el.offsetTop - 120,
                behavior: 'smooth'
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('menuSearch');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const categories = document.querySelectorAll('[id^="cat-"]');
        const menuItems = document.querySelectorAll('.menu-item');

        function filterMenu() {
            const query = searchInput.value.toLowerCase().trim();
            const activeCategory = document.querySelector('.filter-btn.active').dataset.category;

            categories.forEach(cat => {
                const catId = cat.id;
                const itemsInCategory = cat.querySelectorAll('.menu-item');
                let foundInCat = 0;

                itemsInCategory.forEach(item => {
                    const itemName = item.querySelector('.item-name').textContent.toLowerCase();
                    const itemDesc = item.querySelector('.item-desc').textContent.toLowerCase();
                    const matchesSearch = itemName.includes(query) || itemDesc.includes(query);
                    const matchesCategory = activeCategory === 'all' || activeCategory === catId;

                    if (matchesSearch && matchesCategory) {
                        item.classList.remove('d-none');
                        foundInCat++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                // Hide category if no items match or if it's not the selected category
                if (foundInCat > 0) {
                    cat.classList.remove('d-none');
                } else {
                    cat.classList.add('d-none');
                }
            });
        }

        // Category clicks
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const catId = btn.dataset.category;
                if(catId !== 'all') {
                    scrollTo(catId);
                }
                
                filterMenu();
            });
        });

    // Search input
        searchInput.addEventListener('input', filterMenu);
    });

    function openImageModal(url) {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImage').src = url;
        modal.show();
    }
</script>
