import axios from 'axios';
import { displayModal, closeModal } from "../popup";

/**
 * Allows to initialize action to display detail about maze item (actor or movie).
 */
const initActions = () => {
    [...document.querySelectorAll('.show-maze-item-detail-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewMazeItemDetail(element.dataset.detailUrl);
            });
        }
    );

    document.getElementById('build-credits-button')
        && document.getElementById('build-credits-button').addEventListener('click', (event) => {
            buildCredits(event.target.dataset.buildCreditsUrl, event.target.getAttribute('data-progress-url'));
        });
};

/**
 * Allows to display maze item informations in popup.
 * 
 * @param detailUrl Url to call to get detail.
 */
const viewMazeItemDetail = (detailUrl) => {
    axios.get(detailUrl)
        .then(response => {
            displayModal(response.data);
        })
    ;
};

/**
 * Allows to build credits (filmography for actors or casting for movies) with progress bar.
 *
 * @parma buildUrl Url to call to build credits.
 * @parma progressUrl Url to call to get progress when building credits.
 */
const buildCredits = (buildUrl, progressUrl) => {
    axios.post(buildUrl)
        .then(response => {
            closeModal();
            document.location.reload();
        })
    ;

    const progressBar = document.createElement('progress');
    progressBar.classList.add('progress', 'is-large');
    progressBar.setAttribute('value', 0);
    progressBar.setAttribute('max', 100);

    displayModal(progressBar);
    setTimeout(updateProgressBar, 500, progressBar, progressUrl);
};

/**
 * Allows to update progress bar when building filmograhy.
 *
 * @parma progressUrl Url to call to get progress when building credits.
 */
const updateProgressBar = (progressBar, progressUrl) => {
    axios.get(progressUrl)
        .then(response => {
            const { current, total } = response.data;
            if (current > 0 && total > 0) {
                progressBar.setAttribute('max', total);
                progressBar.setAttribute('value', current);
            }
            if (current < total) {
                setTimeout(updateProgressBar, 700, progressBar, progressUrl);
            }
        })
    ;
};

document.getElementById('maze-item-list') && initActions();
