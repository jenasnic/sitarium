import { displayPopup } from '../../component/popup';

class TaglineQuiz {
    popupDelay;
    taglineCount;
    responseSuccessMessage;
    responseFailureMessage;
    quizOverMessage;

    /**
     * @param int popupDelay Time before closing popup automatically.
     */
    constructor(popupDelay) {
        this.popupDelay = popupDelay;
        this.taglineCount = document.querySelectorAll('.tagline-item').length;
        this.responseSuccessMessage = document.getElementById('message-response-success').value;
        this.responseFailureMessage = document.getElementById('message-response-failure').value;
        this.quizOverMessage = document.getElementById('message-quiz-over').value;

        this.initActions();
    };

    /**
     * Init action when selecting response.
     */
    initActions() {
        [...document.querySelectorAll('#response-wrapper .selection-item')].forEach((item) => {
            item.addEventListener('click', (event) => {
                this.checkResponse(item);
            });
        });
        document.getElementById('cheat-button').addEventListener('click', (event) => {
            this.useCheat();
        });
        document.getElementById('replay-button').addEventListener('click', (event) => {
            window.location.reload();
        });
    };

    /**
     * Allow to check if selected movie match with current tagline.
     *
     * @param Element item
     */
    checkResponse(item) {
        if (item.classList.contains('disabled') || item.classList.contains('over')) {
            return;
        }

        const responseCount = document.getElementById('response-count');
        responseCount.innerHTML = `${+responseCount.innerHTML + 1}`;

        const tagline = document.querySelector('.tagline-item.active');

        if (item.dataset.tmdbId != tagline.dataset.tmdbId) {
            displayPopup(this.responseFailureMessage, {autoCloseDelay: this.popupDelay});
            return;
        }

        item.classList.add('disabled');

        if (tagline.dataset.order == this.taglineCount) {
            this.terminateQuiz(true);
            return;
        }

        displayPopup(this.responseSuccessMessage, {
            autoCloseDelay: this.popupDelay,
            onClose: () => {
                window.scroll(0, document.getElementById('tagline-title').offsetTop);
                this.processNextTagline();
            }
        });
    };

    /**
     * Allows to display next tagline.
     */
    processNextTagline() {
        const current = document.querySelector('.tagline-item.active');
        const next = document.querySelector(`.tagline-item[data-order="${+current.dataset.order + 1}"]`);

        current.classList.remove('active');
        if (next) {
            next.classList.add('active');
        }
    };

    /**
     * Allows to cheat to get response.
     */
    useCheat() {
        const responseCount = document.getElementById('response-count');
        responseCount.innerHTML = `${+responseCount.innerHTML + 1}`;

        const tagline = document.querySelector('.tagline-item.active');
        const item = document.querySelector(`.selection-item[data-tmdb-id="${tagline.dataset.tmdbId}"]`);

        item.classList.add('disabled');

        displayPopup(item.dataset.displayName, {
            autoCloseDelay: this.popupDelay,
            onClose: () => {
                this.processNextTagline();
            }
        });

        if (tagline.dataset.order == this.taglineCount) {
            this.terminateQuiz(false);
            return;
        }
    };

    /**
     * Allows to terminate quiz about taglines (i.e. quiz resolved).
     *
     * @param bool showMessage TRUE to display message for quiz over, FALSE either.
     */
    terminateQuiz(showMessage) {
        if (showMessage) {
            const quizOverMessage = document.getElementById('message-quiz-over');
            displayPopup(this.quizOverMessage, {});
        }

        [...document.querySelectorAll('.selection-item:not(.disabled)')].forEach((item) => {
            item.remove();
        });

        const responseWrapper = document.getElementById('response-wrapper');
        [...document.querySelectorAll('.tagline-item')].forEach((tagline) => {
            const item = document.querySelector(`.selection-item[data-tmdb-id="${tagline.dataset.tmdbId}"]`);
            const newItem = item.cloneNode(true);
            newItem.classList.remove('disabled');
            responseWrapper.appendChild(newItem);
            item.remove();
        });

        document.getElementById('tagline-play').classList.add('over');
        document.getElementById('replay-wrapper').classList.add('active');
    };
}

const initQuiz = () => {
    new TaglineQuiz(1400);
};

document.getElementById('tagline-play') && initQuiz();
