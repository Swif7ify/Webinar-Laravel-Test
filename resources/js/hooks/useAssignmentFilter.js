export function useAssignmentFilter(config) {
    const {
        cardsSelector = '.assignment-card',
        searchSelector = '#assignments-search-input',
        filtersContainerSelector = '#assignments-filters',
        paginationContainerSelector = '#js-pagination',
        emptyStateSelector = '.assignments-empty',
        perPage = 5
    } = config;

    let allCards = [];
    let filteredCards = [];
    let currentPage = 1;
    let currentFilter = 'all';
    let searchQuery = '';

    const searchInput = document.querySelector(searchSelector);
    const filtersContainer = document.querySelector(filtersContainerSelector);
    const paginationContainer = document.querySelector(paginationContainerSelector);
    const emptyState = document.querySelector(emptyStateSelector);

    function init() {
        allCards = Array.from(document.querySelectorAll(cardsSelector));
        if (!allCards.length && !emptyState) return;

        filteredCards = [...allCards];

        if (searchInput) {
            searchInput.addEventListener('input', handleSearch);
        }

        if (filtersContainer) {
            filtersContainer.addEventListener('click', handleFilterClick);
        }

        render();
    }

    function handleSearch(e) {
        searchQuery = e.target.value.toLowerCase().trim();
        currentPage = 1;
        applyFilters();
    }

    function handleFilterClick(e) {
        const btn = e.target.closest('.filter-pill');
        if (!btn) return;

        // Update active class
        filtersContainer.querySelectorAll('.filter-pill').forEach(el => el.classList.remove('filter-pill--active'));
        btn.classList.add('filter-pill--active');

        currentFilter = btn.dataset.filter || 'all';
        currentPage = 1;
        applyFilters();
    }

    function applyFilters() {
        filteredCards = allCards.filter(card => {
            const status = card.dataset.status;
            const title = card.dataset.title || '';

            const matchesFilter = currentFilter === 'all' || status === currentFilter;
            const matchesSearch = title.includes(searchQuery);

            return matchesFilter && matchesSearch;
        });

        render();
    }

    function render() {
        // Hide all first
        allCards.forEach(card => {
            card.style.display = 'none';
        });

        // Show empty state if needed
        if (emptyState) {
            emptyState.style.display = filteredCards.length === 0 ? 'block' : 'none';
        }

        // Calculate pagination
        const totalPages = Math.ceil(filteredCards.length / perPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;

        const startIndex = (currentPage - 1) * perPage;
        const endIndex = startIndex + perPage;

        // Show visible cards
        const visibleCards = filteredCards.slice(startIndex, endIndex);
        visibleCards.forEach(card => {
            card.style.display = 'flex'; // our cards use flexbox
        });

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!paginationContainer) return;
        paginationContainer.innerHTML = '';

        if (totalPages <= 1) return;

        const nav = document.createElement('nav');
        nav.className = 'custom-pagination';

        // Previous button
        const prevBtn = document.createElement(currentPage === 1 ? 'span' : 'button');
        prevBtn.className = `pagination-link ${currentPage === 1 ? 'pagination-link--disabled' : ''}`;
        prevBtn.innerHTML = `<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>`;
        if (currentPage > 1) {
            prevBtn.addEventListener('click', () => {
                currentPage--;
                render();
            });
        }
        nav.appendChild(prevBtn);

        // Page numbers (simple approach without dots for now)
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `pagination-link ${currentPage === i ? 'pagination-link--active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                render();
            });
            nav.appendChild(pageBtn);
        }

        // Next button
        const nextBtn = document.createElement(currentPage === totalPages ? 'span' : 'button');
        nextBtn.className = `pagination-link ${currentPage === totalPages ? 'pagination-link--disabled' : ''}`;
        nextBtn.innerHTML = `<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>`;
        if (currentPage < totalPages) {
            nextBtn.addEventListener('click', () => {
                currentPage++;
                render();
            });
        }
        nav.appendChild(nextBtn);

        paginationContainer.appendChild(nav);
    }

    return { init };
}
