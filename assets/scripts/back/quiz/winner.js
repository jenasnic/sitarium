import { displayModal, closeModal } from "../popup";

/**
 * Allows to initialize action to display detail about winner.
 */
const initDetailAction = () => {
    document.querySelectorAll('.show-winner-detail-button').forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewWinnerDetail(element.getAttribute('data-url'));
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
    fetch(url, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            displayModal('winner-detail-modal', response);
        })
    ;
};

document.getElementById('quiz-winner-list') && initDetailAction();
