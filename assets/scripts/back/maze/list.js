import axios from 'axios';
import { initFilterAction } from '../filter';
import { displayModal, closeModal } from '../popup';
import { displayProgressBar } from '../progress-bar';

/**
 * Allows to initialize action to display detail about maze item (actor or movie).
 */
const initActions = () => {
    initFilterAction(initDetailAction);

    initDetailAction();

    document.getElementById('build-credits-button')
        && document.getElementById('build-credits-button').addEventListener('click', (event) => {
            buildCredits(event.target.dataset.buildCreditsUrl, event.target.getAttribute('data-progress-url'));
        });
};

/**
 * Allows to display info for maze items.
 */
const initDetailAction = () => {
    [...document.querySelectorAll('.show-maze-item-detail-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewMazeItemDetail(element.dataset.detailUrl);
            });
        }
    );
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
 * @param buildUrl Url to call to build credits.
 * @param progressUrl Url to call to get progress when building credits.
 */
const buildCredits = (buildUrl, progressUrl) => {
    axios.post(buildUrl);

    displayProgressBar(progressUrl, () => {
        document.location.reload();
    });
};

document.getElementById('maze-item-list') && initActions();
