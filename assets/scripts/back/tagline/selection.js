
/**
 * Allows to set selected movies before submitting form.
 */
const initActions = () => {
    document.getElementById('movie-selection-form').addEventListener('submit', (event) => {
        const movieSelectionIds = [...document.querySelectorAll('input[data-movie-selection]:checked')].map(
            checkbox => checkbox.dataset.tmdbId
        );

        document.getElementById('movie-selection-ids').value = movieSelectionIds.join(',');
    });
};

document.getElementById('tagline-movie-selection') && initActions();
