import axios from 'axios';
import PerfectScrollbar from 'perfect-scrollbar';
import { displayPopup } from '../../component/popup';

class MazePlayer {
    mazeItemWidth;
    mazeResponseWidth;
    mazeItemMargin;
    popupDelay;
    responseFoundMessage;
    quizOverMessage;

    /**
     * @param int mazeItemWidth Total width of items in scrollable element.
     * @param int mazeResponseWidth Total width of response in scrollable element.
     * @param int mazeItemMargin Width of margin used on items in scrollable element.
     * @param int popupDelay Time before closing popup automatically.
     */
    constructor(mazeItemWidth, mazeResponseWidth, mazeItemMargin, popupDelay) {
        this.mazeItemWidth = mazeItemWidth;
        this.mazeResponseWidth = mazeResponseWidth;
        this.mazeItemMargin = mazeItemMargin;
        this.popupDelay = popupDelay;

        this.responseFoundMessage = document.getElementById('message-response-found').value;
        this.quizOverMessage = document.getElementById('message-quiz-over').value;

        this.initScroll();
        this.initHelper();
        this.initActions();
        this.refreshForMobile();
    };

    /**
     * Allows to initialize scrollbar.
     */
    initScroll() {
        const scrollWrapper = document.getElementById('scroll-wrapper');
        const itemCount = scrollWrapper.querySelectorAll('.maze-item').length;
        const width = this.mazeItemWidth * itemCount;

        document.getElementById('maze-items').style.width = `${width}px`;
        new PerfectScrollbar(scrollWrapper);
    };

    /**
     * Allows to show/hide helper area (for desktop and mobile) + allows to select a response among help items.
     */
    initHelper() {
        document.getElementById('toggle-helper-button').addEventListener('click', (event) => {
            event.target.classList.toggle('fa-plus-circle');
            event.target.classList.toggle('fa-minus-circle');
            document.getElementById('toggle-wrapper').classList.toggle('active');
        });
        
        document.getElementById('mobile-helper-button').addEventListener('click', (event) => {
            document.getElementById('response-wrapper').classList.add('mobile-active');
        });

        document.getElementById('response-wrapper').addEventListener('click', (event) => {
            document.getElementById('response-wrapper').classList.remove('mobile-active');
        });

        [...document.getElementById('response-wrapper').querySelectorAll('.selection-item')].forEach(
            (element) => {
                element.addEventListener('click', (event) => {
                    document.getElementById('response-wrapper').classList.remove('mobile-active');
                    this.submitResponse(element.dataset.displayName, true);
                });
            }
        );
    };

    /**
     * Allows to initialize actions when playing maze.
     */
    initActions() {
        document.getElementById('answer-button').addEventListener('click', (event) => {
            this.submitResponse(document.getElementById('response').value, false);
        });
        document.getElementById('trick-button').addEventListener('click', (event) => {
            const levelElement = document.getElementById('maze-level');
            const level = levelElement ? levelElement.value : null
            this.displayTrick(document.querySelector('.maze-item.active').dataset.tmdbId, level);
        });
        document.getElementById('replay-button').addEventListener('click', (event) => {
            window.location.assign(event.target.dataset.replayUrl);
        });
    };

    /**
     * Allows to send response for maze progress.
     *
     * @param string response
     * @param bool backToMaze
     */
    submitResponse(response, backToMaze) {
        const responseUrl = document.getElementById('maze-play').dataset.responseUrl;
        const currentItem = document.querySelector('.maze-item.active');
        const nextItem = document.querySelector(`.maze-item[data-order="${+currentItem.dataset.order + 1}"]`);

        axios.post(responseUrl, {
            currentTmdbId: currentItem.dataset.tmdbId,
            nextTmdbId: nextItem.dataset.tmdbId,
            response: response
        })
            .then((response) => {
                const responseCount = document.getElementById('response-count');
                responseCount.innerHTML = `${+responseCount.innerHTML + 1}`;

                if (response.data.success) {
                    const mazeResolved = this.progressMaze();

                    if (backToMaze) {
                        window.scroll(0, document.getElementById('action-wrapper').offsetTop);
                    }

                    this.addMazeResponse(response.data);

                    if (mazeResolved) {
                        this.terminateMaze();
                        displayPopup(this.quizOverMessage);
                    } else {
                        displayPopup(this.responseFoundMessage, {autoCloseDelay: this.popupDelay});
                    }
                } else {
                    displayPopup(response.data.message, {autoCloseDelay: this.popupDelay});
                }
            })
        ;
    };

    /**
     * Allows to send request for a trick.
     *
     * @param int tmdbId
     * @param int level
     */
    displayTrick(tmdbId, level) {
        const trickUrl = document.getElementById('maze-play').dataset.trickUrl;

        const parameters = {tmdbId: tmdbId};
        if (level) {
            parameters.level = level;
        }

        axios.post(trickUrl, parameters)
            .then((response) => {
                if (response.data.success) {
                    const list = document.createElement('ul');
                    response.data.responses.forEach((trick) => {
                        const item = document.createElement('li');
                        item.innerHTML = trick;
                        list.appendChild(item);
                    });

                    displayPopup(list.outerHTML);
                }
            })
        ;
    };

    /**
     * Allows to progress maze with following actions:
     * - move to next maze item
     * - move cursor
     * - check if maze is over.
     *
     * @param object response Response as JSON object with displayName, tmdbLink and pictureUrl.
     * @param string displayName
     *
     * @return bool True if end of maze has been reached (maze resolved), false either.
     */
    progressMaze() {
        const currentItem = document.querySelector('.maze-item.active');
        currentItem.classList.remove('active');

        const newIndex = +currentItem.dataset.order + 1;
        const nextItem = document.querySelector(`.maze-item[data-order="${newIndex}"]`);
        nextItem.classList.add('active');

        if (nextItem.classList.contains('last')) {
            return true;
        } else {
            const cursor = document.getElementById('maze-cursor');
            document.getElementById('maze-cursor').style.left = `${cursor.offsetLeft + this.mazeItemWidth}px`;

            this.centerCursorScroll();
            this.refreshForMobile();

            return false;
        }
    };

    /**
     * Allows to add response as picture with link on TMDB.
     *
     * @param object response Response as JSON object with displayName, tmdbLink and pictureUrl.
     */
    addMazeResponse(response) {
        const responsePicture = document.createElement('img');
        responsePicture.src = response.pictureUrl;
        responsePicture.alt = response.displayName;

        const positionLeft = ((+document.querySelector('.maze-item.active').dataset.order - 1) * this.mazeItemWidth) - (this.mazeResponseWidth / 2);

        const responseLink = document.createElement('a');
        responseLink.href = response.tmdbLink;
        responseLink.target = '_blank';
        responseLink.title = response.displayName;
        responseLink.classList.add('maze-response');
        responseLink.appendChild(responsePicture);
        responseLink.style.left = `${positionLeft}px`;

        document.getElementById('maze-items').appendChild(responseLink);
    };

    /**
     * Allows to set scroll in order to center cursor.
     */
    centerCursorScroll() {
        const cursor = document.getElementById('maze-cursor');
        const container = document.getElementById('maze-wrapper');

        const scrollOffset = this.mazeItemWidth - (cursor.offsetLeft % this.mazeItemWidth) + (2 * this.mazeItemMargin);
        const newPosition = cursor.offsetLeft - (container.offsetWidth / 2) + scrollOffset;
        document.getElementById('scroll-wrapper').scroll(newPosition, 0);
    };

    /**
     * Allows to terminate maze, i.e. show/remove/hide blocks for desktop and mobiles.
     */
    terminateMaze() {
        // Remove block for desktop
        const cursor = document.getElementById('maze-cursor');
        cursor.parentNode.removeChild(cursor);
        const actionWrapper = document.getElementById('action-wrapper');
        actionWrapper.parentNode.removeChild(actionWrapper);
        const helperWrapper = document.getElementById('helper-wrapper');
        helperWrapper.parentNode.removeChild(helperWrapper);

        document.querySelector('.maze-item.active').classList.remove('active');

        // Add/remove class for mobile
        document.querySelector('.mobile-maze-item.current').classList.remove('current');
        document.querySelector('.mobile-maze-item.next').classList.remove('next');
        document.getElementById('mobile-wrapper').classList.add('over');

        // Display button to replay
        document.getElementById('replay-wrapper').classList.add('active');
    };

    /**
     * Allows to update mobile wrapper with current progress informations (i.e. update both maze item current/next).
     */
    refreshForMobile() {
        [...document.querySelectorAll('.mobile-maze-item')].forEach((item) => {
            item.classList.remove('current');
            item.classList.remove('next');
        });

        const currentItem = document.querySelector('.maze-item.active');
        const mobileCurrentItem = document.querySelector(`.mobile-maze-item[data-order="${currentItem.dataset.order}"]`);
        const mobileNextItem = document.querySelector(`.mobile-maze-item[data-order="${+currentItem.dataset.order + 1}"]`);

        mobileCurrentItem.classList.add('current');
        mobileNextItem.classList.add('next');
    }
}

/**
 * Allows to initialize maze page (for actors and movies).
 */
const initMaze = () => {
    new MazePlayer(224, 104, 20, 1400);
    document.getElementById('response').focus();
}

document.getElementById('maze-play') && initMaze();
