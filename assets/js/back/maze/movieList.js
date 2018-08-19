import { displayModalWithContent, closeModal } from "../popup";

// Define action when user click on detail for movie
document.getElementById('movie-list') && document.querySelectorAll('.show-movie-detail-button').forEach(
    function(element) {
        element.addEventListener('click', function(event) {
            viewMovieDetail(element.getAttribute('data-id'));
        });
    }
);

// Define action when user want to build casting for all movies
document.getElementById('build-casting-button')
    && document.getElementById('build-casting-button').addEventListener('click', function() {
        buildCasting();
    });

/**
 * Allows to display movie's informations in popup.
 * 
 * @param movieId Identifiant of movie we want to display informations.
 */
const viewMovieDetail = (movieId) => {
    fetch(`/admin/maze/movie/view/${movieId}`, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            displayModalWithContent('movie-detail-modal', response);
        });
};

/**
 * Allows to build casting for movies with progress bar.
 */
const buildCasting = () => {
    fetch('/admin/maze/movie/casting/build', {credentials: 'same-origin'})
        .then(response => response.json())
        .then(response => {
            closeModal('progress-bar-modal');
            document.location.reload();
        });

    displayModalWithContent('progress-bar-modal');
    setTimeout(updateProgressBar, 200);
};

/**
 * Allows to update progress bar when building casting.
 */
const updateProgressBar = () => {
    fetch('/admin/maze/movie/casting/progress', {credentials: 'same-origin'})
        .then(response => response.json())
        .then(response => {
            const { current, total } = response;
            if (current > 0 && total > 0) {
                const progressBar = document.querySelector('#progress-bar-modal progress.progress');
                progressBar.setAttribute('max', total);
                progressBar.setAttribute('value', current);
            }
            if (current < total) {
                setTimeout(updateProgressBar, 700);
            }
        });
};
