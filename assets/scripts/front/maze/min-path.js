
/**
 * Allows to initialize action when submitting selection for min path (for actors and movies).
 */
const initSubmitForm = () => {
    document.getElementById('path-selection-form').addEventListener('submit', (event) => {
        const selectedItems = document.querySelectorAll('.selection-item.active');

        document.getElementById('startTmdbId').value = selectedItems[0].dataset.tmdbId;
        document.getElementById('endTmdbId').value = selectedItems[1].dataset.tmdbId;
    });
};

document.getElementById('maze-min-path') && initSubmitForm();
