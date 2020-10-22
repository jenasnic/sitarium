import axios from 'axios';
import { initFilterAction } from '../filter';
import { displayModal, closeModal } from '../popup';
import { activateProgressBar } from '../progress-bar';

/**
 * Allows to initialize actions for taglines.
 */
const initActions = () => {
    initFilterAction(initDetailAction);

    initDetailAction();

    const progressBar = document.querySelector('progress[data-current-build-process]');
    if (progressBar) {
        activateProgressBar(
            progressBar,
            progressBar.dataset.progressUrl,
            () => { document.location.reload(true); }
        );
    }
};

/**
 * Allows to display info for movie.
 */
const initDetailAction = () => {
    [...document.querySelectorAll('.show-tagline-movie-detail-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewMovieDetail(element.dataset.detailUrl);
            });
        }
    );
};

/**
 * Allows to display movie informations in popup.
 * 
 * @param detailUrl Url to call to get detail.
 */
const viewMovieDetail = (detailUrl) => {
    axios.get(detailUrl)
        .then(response => {
            displayModal(response.data);
        })
    ;
};

document.getElementById('tagline-movie-list') && initActions();
