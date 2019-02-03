import axios from 'axios';
import { displayModal, closeModal } from '../popup';
import { displayProgressBar } from '../progress-bar';

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

    document.getElementById('link-tmdb-button').addEventListener('click', (event) => {
        buildTmdbLink(event.target.dataset.tmdbLinkUrl, event.target.dataset.progressUrl);
    });
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

/**
 * Allows to link movies from quiz with TMDB with progress bar.
 *
 * @parma linkUrl Url to call to link movies with TMDB.
 * @parma progressUrl Url to call to get progress when linking movies.
 */
const buildTmdbLink = (tmdbLinkUrl, progressUrl) => {
    axios.post(tmdbLinkUrl)
        .then(response => {
            closeModal('progress-bar-modal');
            document.location.reload();
        })
    ;

    displayProgressBar(progressUrl);
};

document.getElementById('quiz-responses-wrapper') && initQuizResponseActions();
