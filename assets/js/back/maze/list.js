import { displayModal, closeModal } from "../popup";

/**
 * Allows to initialize action to display detail about maze item (actor or movie).
 */
const initDetailAction = () => {
    const baseUrl = document.getElementById('maze-item-list').getAttribute('data-base-detail-url');
    document.querySelectorAll('.show-maze-item-detail-button').forEach(
        function(element) {
            element.addEventListener('click', function(event) {
                viewMazeItemDetail(baseUrl, element.getAttribute('data-id'));
            });
        }
    );
};

/**
 * Allows to display maze item informations in popup.
 * 
 * @param url Url to call to get detail. `/admin/maze/actor/view/${actorId}` // `/admin/maze/movie/view/${movieId}`
 * @param id Identifier of maze item we want to display detail.
 */
const viewMazeItemDetail = (baseUrl, id) => {
    const detailUrl = baseUrl + id;
    fetch(detailUrl, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            displayModal('maze-item-detail-modal', response);
        });
};

/**
 * Allows to build credits (filmography for actors or casting for movies) with progress bar.
 *
 * @parma buildUrl Url to call to build credits.
 * @parma progressUrl Url to call to get progress when building credits.
 */
const buildCredits = (buildUrl, progressUrl) => {
    fetch(buildUrl, {credentials: 'same-origin'})
        .then(response => response.json())
        .then(response => {
            closeModal('progress-bar-modal');
            document.location.reload();
        });

    displayModal('progress-bar-modal');
    setTimeout(updateProgressBar, 500, progressUrl);
};

/**
 * Allows to update progress bar when building filmogrpahy.
 * 
 * @parma url Url to call to get progress when building credits.
 */
const updateProgressBar = (progressUrl) => {
    fetch(progressUrl, {credentials: 'same-origin'})
        .then(response => response.json())
        .then(response => {
            const { current, total } = response;
            if (current > 0 && total > 0) {
                const progressBar = document.querySelector('#progress-bar-modal progress.progress');
                progressBar.setAttribute('max', total);
                progressBar.setAttribute('value', current);
            }
            if (current < total) {
                setTimeout(updateProgressBar, 700, progressUrl);
            }
        });
};

document.getElementById('maze-item-list') && initDetailAction();

document.getElementById('build-credits-button')
    && document.getElementById('build-credits-button').addEventListener('click', function(event) {
        buildCredits(event.target.getAttribute('data-build-url'), event.target.getAttribute('data-progress-url'));
    });
