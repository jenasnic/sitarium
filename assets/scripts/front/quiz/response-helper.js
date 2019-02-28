import axios from 'axios';
import Drift from 'drift-zoom';
import { displayPopup } from '../../component/popup';
import { getRelativePosition } from '../../component/position';

export default class ResponseHelper {
    quizId;
    trickUrl;
    pictureWidth;
    pictureHeight;
    popupDelay;
    isTricking;
    zoom;

    /**
     * @param int quizId
     * @param string trickUrl Callback URL to request trick.
     * @param int pictureWidth Original picture width (used to calculate location when requesting trick).
     * @param int pictureHeight Original picture height (used to calculate location when requesting trick).
     * @param int popupDelay Time before closing popup automatically.
     */
    constructor(quizId, trickUrl, pictureWidth, pictureHeight, popupDelay) {
        this.quizId = quizId;
        this.trickUrl = trickUrl;
        this.pictureWidth = pictureWidth;
        this.pictureHeight = pictureHeight;
        this.popupDelay = popupDelay;
        this.isTricking = false;

        this.initZoom();
    };

    /**
     * Allows to create zoom feature on picture.
     */
    initZoom() {
        this.zoom = new Drift(document.getElementById('picture-quiz'), {
            containInline: true,
            inlineOffsetX: 0,
            inlineOffsetY: 0,
            inlinePane: true,
            showWhitespaceAtEdges: true
        });
    };

    /**
     * Allows to process 'trick' when clicking on picture.
     *
     * @param Event event
     * @param bool isMobile TRUE for event from mobile (touchstart), FALSE either (click)
     */
    triggerTrickEvent(event, isMobile) {
        if (this.isTricking) {
            const currentPosition = getRelativePosition(event, isMobile);
            const pictureQuiz = document.getElementById('picture-quiz');
            const positionInTruePicture = {
                positionX: currentPosition.positionX * this.pictureWidth / pictureQuiz.offsetWidth,
                positionY: currentPosition.positionY * this.pictureHeight / pictureQuiz.offsetHeight
            };

            this.requestTrick(positionInTruePicture);
        }
    };

    /**
     * Allows to switch between tricking mode (to request trick) and zoom mode (to explore picture).
     *
     * @param string mode
     */
    toggleHelperMode(mode) {
        this.isTricking = ('trick' === mode);
        this.isTricking ? this.zoom.disable() : this.zoom.enable();
        document.getElementById('picture-quiz').style.cursor = this.isTricking ? 'help' : 'default';
    };

    /**
     * Allows to request trick for specified position in picture.
     *
     * @param position
     */
    requestTrick(position) {
        const parameters = {
            quizId: this.quizId,
            ...position
        };

        axios.post(this.trickUrl, parameters)
            .then((response) => {
                if (!response.data.success) {
                    displayPopup(response.data.message, {autoCloseDelay: this.popupDelay});
                    return;
                }

                const list = document.createElement('ul');
                response.data.trick.forEach((trick) => {
                    const item = document.createElement('li');
                    item.innerHTML = trick;
                    list.appendChild(item);
                });

                displayPopup(list.outerHTML);
            })
        ;
    };
}
