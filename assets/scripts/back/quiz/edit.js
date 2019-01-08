import { displayModal, closeModal } from "../popup";

/**
 * Allows to define action when editing a response of quiz
 *
 * @param form
 */
const initQuizResponseForm = (url, form) => {
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        fetch(url, {
            credentials: 'same-origin',
            method: 'POST',
            body: new FormData(form),
        })
            .then(response => response.json())
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

/**
 * Allows to define actions for responses of quiz : add/edit/delete
 */
const initQuizResponseAction = () => {
    document.getElementById('add-quiz-response-button').addEventListener('click', (event) => {
        editQuizResponse(event.target.getAttribute('data-url'));
    });

    document.getElementById('link-tmdb-button').addEventListener('click', (event) => {
        buildTmdbLink(event.target.getAttribute('data-link-url'), event.target.getAttribute('data-progress-url'))
    });

    [...document.querySelectorAll('.edit-quiz-response-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                editQuizResponse(element.getAttribute('data-url'));
            });
        }
    );

    [...document.querySelectorAll('.delete-quiz-response-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                deleteQuizResponse(element.getAttribute('data-url'));
            });
        }
    );
};

/**
 * Allows to edit quiz response.
 *
 * @param url Url to call to edit response.
 */
const editQuizResponse = (url) => {
    fetch(url, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            displayModal('quiz-form-response-modal', response);
            initQuizResponseForm(url, document.querySelector('#quiz-form-response-modal .modal-card-body form'));
        })
    ;
};

/**
 * Allows to remove quiz response.
 *
 * @param url Url to call to remove response.
 */
const deleteQuizResponse = (url) => {
    if (confirm('Confirmer la suppression ?')) {
        fetch(url, {credentials: 'same-origin'})
            .then(response => reloadQuizResponses())
        ;
    }
};

/**
 * Allows to load responses for quiz.
 */
const reloadQuizResponses = () => {
    const url = document.getElementById('quiz-responses-wrapper').getAttribute('data-responses-url');
    fetch(url, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            document.getElementById('quiz-responses-wrapper').innerHTML = response;
            initQuizResponseAction();
        })
    ;
};

/**
 * Allows to link movies from quiz with TMDB with progress bar.
 *
 * @parma linkUrl Url to call to link movies with TMDB.
 * @parma progressUrl Url to call to get progress when linking movies.
 */
const buildTmdbLink = (linkUrl, progressUrl) => {
    fetch(linkUrl, {credentials: 'same-origin'})
        .then(response => response.json())
        .then(response => {
            closeModal('progress-bar-modal');
            document.location.reload();
        })
    ;

    displayModal('progress-bar-modal');
    setTimeout(updateProgressBar, 500, progressUrl);
};

/**
 * Allows to update progress bar when linking movies with TMDB.
 *
 * @parma progressUrl Url to call to get progress when linking movies.
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
        })
    ;
};

document.getElementById('quiz-responses-wrapper') && initQuizResponseAction();
