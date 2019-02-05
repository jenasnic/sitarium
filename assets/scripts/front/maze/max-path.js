
/**
 * Allows to initialize action when submitting selection for max path (for actors and movies).
 */
const initSubmitForm = () => {
    document.getElementById('path-selection-form').addEventListener('submit', (event) => {
        const selectedItems = [...document.querySelectorAll('.selection-item.active')];
        const tmdbIds = selectedItems.map(item => item.dataset.tmdbId);

        document.getElementById('tmdbIds').value = tmdbIds.join(',');
    });
};

document.getElementById('maze-max-path') && initSubmitForm();
