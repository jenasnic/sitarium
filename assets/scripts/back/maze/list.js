import axios from 'axios';
import { initFilterAction } from '../filter';
import { displayModal, closeModal } from '../popup';
import { activateProgressBar } from '../progress-bar';

/**
 * Allows to initialize action to display detail about maze item (actor or movie).
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

document.getElementById('maze-item-list') && initActions();
