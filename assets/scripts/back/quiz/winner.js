import axios from 'axios';
import { initFilterAction } from '../filter';
import { displayModal } from '../popup';

/**
 * Allows to initialize action to display detail about winner.
 */
const initActions = () => {
    initFilterAction(initDetailAction);
    initDetailAction();
};

/**
 * Allows to display info for winner.
 */
const initDetailAction = () => {
    [...document.querySelectorAll('.show-winner-detail-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewWinnerDetail(element.dataset.winnerDetailUrl);
            });
        }
    );
};

/**
 * Allows to display winner informations in popup.
 * 
 * @param url Url to call to get detail.
 */
const viewWinnerDetail = (url) => {
    axios.get(url)
        .then(response => {
            displayModal(response.data);
        })
    ;
};

document.getElementById('quiz-winner-list') && initActions();
