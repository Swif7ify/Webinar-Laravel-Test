import { useAssignmentFilter } from '../hooks';

export function initAssignments() {
    const page = document.querySelector('.my-assignments-page');
    if (!page) return;

    useAssignmentFilter({
        cardsSelector: '.assignment-card',
        searchSelector: '#assignments-search-input',
        filtersContainerSelector: '#assignments-filters',
        paginationContainerSelector: '#js-pagination',
        emptyStateSelector: '.assignments-empty',
        perPage: 5
    }).init();
}
