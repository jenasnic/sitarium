import axios from 'axios';
import { displayModal, closeModal } from '../popup';
import { activateProgressBar } from '../progress-bar';

/**
 * Allows to define actions for responses of quiz : add/edit/delete + link with TMDB
 */
const initQuizResponseActions = () => {
    document.getElementById('add-quiz-response-button').addEventListener('click', (event) => {
        editQuizResponse(event.target.dataset.addResponseUrl);
    });

    [...document.querySelectorAll('.edit-quiz-response-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                editQuizResponse(element.dataset.editResponseUrl);
            });
        }
    );

    [...document.querySelectorAll('.delete-quiz-response-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                deleteQuizResponse(element.dataset.deleteResponseUrl);
            });
        }
    );

    const progressBar = document.querySelector('progress[data-current-build-process]');
    if (progressBar) {
        activateProgressBar(
            progressBar,
            progressBar.dataset.progressUrl,
            () => {
                document.getElementById('pending-process-wrapper').remove();
                document.querySelector('button[form="link-tmdb-form"]').disabled = false;
            }
        );
    }
};

/**
 * Allows to edit quiz response.
 *
 * @param string url Url to call to edit response.
 */
const editQuizResponse = (url) => {
    axios.get(url)
        .then(response => {
            const form = new DOMParser().parseFromString(response.data, 'text/html').querySelector('form');
            initQuizResponseForm(url, form);
            displayModal(form);
        })
    ;
};

/**
 * Allows to remove quiz response.
 *
 * @param string url Url to call to remove response.
 */
const deleteQuizResponse = (url) => {
    if (confirm('Confirmer la suppression ?')) {
        axios.get(url)
            .then(response => reloadQuizResponses())
        ;
    }
};

/**
 * Allows to load responses for quiz.
 */
const reloadQuizResponses = () => {
    const url = document.getElementById('quiz-responses-wrapper').dataset.responsesUrl;
    axios.get(url)
        .then(response => {
            document.getElementById('quiz-responses-wrapper').innerHTML = response.data;
            initQuizResponseActions();
        })
    ;
};

/**
 * Allows to define action when editing a response of quiz (for AJAX submission).
 *
 * @param string url URL used to submit form.
 * @param Element form
 */
const initQuizResponseForm = (url, form) => {
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        axios({
            method: 'post',
            url: url,
            data: new FormData(form),
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        })
            .then(response => {
                if (false === response.success) {
                    alert(response.message);
                } else {
                    closeModal('quiz-form-response-modal');
                    reloadQuizResponses();
                }
            })
        ;
    });
}

document.getElementById('quiz-responses-wrapper') && initQuizResponseActions();
