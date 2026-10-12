/**
 * Hook to manage simple tab switching without page reload.
 * 
 * @param {string} tabsContainerId - The ID of the container holding the tab buttons
 * @param {string} panelsContainerId - The ID of the container holding the tab panels
 * @param {string} activeClass - The class to apply to active tabs/panels (default: 'active')
 */
export function useTabs(tabsContainerId, panelsContainerId, activeClass = 'active') {
    const storageKey = `active_tab_${window.location.pathname}_${tabsContainerId}`;

    const activateTab = (targetId, tabButtons, tabPanels) => {
        const targetPanel = document.getElementById(targetId);
        if (!targetPanel) return;

        // Deactivate all
        tabButtons.forEach(btn => btn.classList.remove(activeClass));
        tabPanels.forEach(panel => panel.classList.remove(activeClass));

        // Activate button
        const button = Array.from(tabButtons).find(btn => btn.dataset.tabTarget === targetId);
        if (button) {
            button.classList.add(activeClass);
        }

        // Activate panel
        targetPanel.classList.add(activeClass);

        // Save to storage
        localStorage.setItem(storageKey, targetId);
    };

    const init = () => {
        const tabsContainer = document.getElementById(tabsContainerId);
        const panelsContainer = document.getElementById(panelsContainerId);

        if (!tabsContainer || !panelsContainer) return;

        const tabButtons = tabsContainer.querySelectorAll('[data-tab-target]');
        const tabPanels = panelsContainer.querySelectorAll('[data-tab-content]');

        // Check for stored tab or hash
        const storedTab = localStorage.getItem(storageKey);
        const hashTab = window.location.hash ? window.location.hash.substring(1) : null;
        const initialTab = hashTab || storedTab;

        if (initialTab && document.getElementById(initialTab)) {
            activateTab(initialTab, tabButtons, tabPanels);
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.dataset.tabTarget;
                activateTab(targetId, tabButtons, tabPanels);
                
                // Update URL hash without jumping the page
                if (history.replaceState) {
                    history.replaceState(null, null, `#${targetId}`);
                } else {
                    window.location.hash = targetId;
                }
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}
