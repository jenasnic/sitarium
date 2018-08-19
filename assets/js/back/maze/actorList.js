import { displayModalWithContent, closeModal } from "../popup";

// Define action when user click on detail for actor
document.getElementById('actor-list') && document.querySelectorAll('.show-actor-detail-button').forEach(
    function(element) {
        element.addEventListener('click', function(event) {
            viewActorDetail(element.getAttribute('data-id'));
        });
    }
);

// Define action when user want to build filmography for all actors
document.getElementById('build-filmography-button')
    && document.getElementById('build-filmography-button').addEventListener('click', function() {
        buildFilmography();
    });

/**
 * Allows to display actor's informations in popup.
 * 
 * @param actorId Identifiant of actor we want to display informations.
 */
const viewActorDetail = (actorId) => {
    fetch(`/admin/maze/actor/view/${actorId}`, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            displayModalWithContent('actor-detail-modal', response);
        });
};

/**
 * Allows to build filmography for actors with progress bar.
 */
const buildFilmography = () => {
    fetch('/admin/maze/actor/filmography/build', {credentials: 'same-origin'})
        .then(response => response.json())
        .then(response => {
            closeModal('progress-bar-modal');
            document.location.reload();
        });

    displayModalWithContent('progress-bar-modal');
    setTimeout(updateProgressBar, 200);
};

/**
 * Allows to update progress bar when building filmogrpahy.
 */
const updateProgressBar = () => {
    fetch('/admin/maze/actor/filmography/progress', {credentials: 'same-origin'})
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
