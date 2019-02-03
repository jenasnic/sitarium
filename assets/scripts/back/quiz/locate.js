import axios from 'axios';
import { getOffsetElement } from '../../component/position';

/**
 * Class to initialize response localizer (i.e. picture with mouse selection to localize some response items).
 */
class ResponseLocalizer {
    canvas;
    canvasInfo;
    cursorInfo;
    selectionElement;
    callbackSelection;

    /**
     * @param canvas element used as canvas with following data properties :
     *     - pictureWidth : width of picture used as background for canvas
     *     - pictureHeight : height of picture used as background for canvas
     *     - pictureUrl : URL of picture used as background for canvas
     * @param callbackSelection callback function called after selection done, taken one parameter with :
     *     - positionX : starting offset left for localisation
     *     - positionY : starting offset top for localisation
     *     - width : width of selection for localisation
     *     - height : height of selection for localisation
     */
    constructor(canvas, callbackSelection) {
        this.canvas = canvas;
        this.cursorInfo = {isDrawing: false};
        this.selectionElement = null;
        this.callbackSelection = callbackSelection;

        this.initCanvas();
        this.initActions();
    }

    /**
     * Allows to initialize canvas with picture.
     */
    initCanvas() {
        const pictureWidth = this.canvas.dataset.pictureWidth;
        const pictureHeight = this.canvas.dataset.pictureHeight;
        const pictureUrl = this.canvas.dataset.pictureUrl;

        let maxWidth = Math.min((window.innerWidth * 0.95), pictureWidth);
        let maxHeight = Math.min((window.innerHeight * 0.95), pictureHeight);

        const maxHeightForMaxWidth = (maxWidth * pictureHeight) / pictureWidth;
        const maxWidthForMaxHeight = (maxHeight * pictureWidth) / pictureHeight;

        if (maxHeightForMaxWidth > maxHeight) {
            maxWidth = (maxHeight * pictureWidth ) / pictureHeight;
            maxHeight = (maxWidth * pictureHeight ) / pictureWidth;
        } else if (maxWidthForMaxHeight > maxWidth) {
            maxHeight = (maxWidth * pictureHeight ) / pictureWidth;
            maxWidth = (maxHeight * pictureWidth ) / pictureHeight;
        }

        this.canvas.style.width  = `${maxWidth}px`;
        this.canvas.style.height = `${maxHeight}px`;
        this.canvas.style.backgroundImage = `url('${pictureUrl}')`;

        const canvasOffset = getOffsetElement(this.canvas);

        this.canvasInfo = {
            offsetX: canvasOffset.left,
            offsetY: canvasOffset.top,
            width: maxWidth,
            height: maxHeight
        };
    };

    /**
     * Allows to initialize actions for canvas (i.e. make selection, validate selcetion...).
     */
    initActions() {
        this.canvas.addEventListener('mousedown', (event) => { this.startDrawing(event); });
        this.canvas.addEventListener('mouseup', (event) => { this.endDrawing(event); });
        this.canvas.addEventListener('mousemove', (event) => { this.onMove(event); });
    };

    /**
     * Allows to start selection.
     */
    startDrawing(e) {
        if (null !== this.selectionElement) {
            this.selectionElement.parentNode.removeChild(this.selectionElement);
        }

        clearLocationState();

        this.cursorInfo = {
            startX: e.clientX - this.canvasInfo.offsetX,
            startY: e.clientY - this.canvasInfo.offsetY + window.scrollY,
            isDrawing: true
        };

        this.selectionElement = document.createElement('div');
        this.selectionElement.style.left = `${this.cursorInfo.startX}px`;
        this.selectionElement.style.top = `${this.cursorInfo.startY}px`;
        this.selectionElement.classList.add('selection');
        this.canvas.appendChild(this.selectionElement);
        this.canvas.style.cursor = "crosshair";
    };

    /**
     * Allows to finalize selection.
     */
    endDrawing(e) {
        this.cursorInfo.isDrawing = false;
        this.canvas.style.cursor = "default";

        const selectionInfo = {
            positionX: (this.selectionElement.offsetLeft * this.canvas.dataset.pictureWidth) / this.canvasInfo.width,
            positionY: (this.selectionElement.offsetTop * this.canvas.dataset.pictureHeight) / this.canvasInfo.height,
            width: (this.selectionElement.offsetWidth * this.canvas.dataset.pictureWidth) / this.canvasInfo.width,
            height: (this.selectionElement.offsetHeight * this.canvas.dataset.pictureHeight) / this.canvasInfo.height
        };
        this.callbackSelection(selectionInfo);
    };

    /**
     * Allows to draw selection when moving mouse.
     */
    onMove(e) {
        if (this.cursorInfo.isDrawing) {
            this.cursorInfo.currentX = e.clientX - this.canvasInfo.offsetX;
            this.cursorInfo.currentY = e.clientY - this.canvasInfo.offsetY + window.scrollY;
            this.refreshSelection();
        }
    };

    /**
     * Allows to refresh selection render after mouse position has changed.
     */
    refreshSelection() {
        const newWidth = Math.abs(this.cursorInfo.currentX - this.cursorInfo.startX);
        const newHeight = Math.abs(this.cursorInfo.currentY - this.cursorInfo.startY);
        const newLeft = Math.min(this.cursorInfo.currentX, this.cursorInfo.startX);
        const newTop = Math.min(this.cursorInfo.currentY, this.cursorInfo.startY);

        this.selectionElement.style.width = `${newWidth}px`;
        this.selectionElement.style.height = `${newHeight}px`;
        this.selectionElement.style.left = `${newLeft}px`;
        this.selectionElement.style.top = `${newTop}px`;
    };
}

/**
 * Allows to initialize localizer for responses of current quiz.
 */
const initialize = () => {
    new ResponseLocalizer(document.getElementById('canvas'), refreshSelectionInfo);
    document.getElementById('next-button').addEventListener('click', processNextResponse);
    document.getElementById('validate-button').addEventListener('click', submitResponseLocation);
    document.getElementById('quiz-responses').addEventListener('change', displayResponseLocation);
}

/**
 * Allows to set hidden fields with selection informations for further validation (to set response localisation).
 */
const refreshSelectionInfo = (selectionInfo) => {
    document.getElementById('selection-position-x').value = selectionInfo.positionX;
    document.getElementById('selection-position-y').value = selectionInfo.positionY;
    document.getElementById('selection-width').value = selectionInfo.width;
    document.getElementById('selection-height').value = selectionInfo.height;
};

/**
 * Allows to select next response to locate.
 */
const processNextResponse = (event) => {
    const selectedOption = document.querySelector('#quiz-responses option:checked');
    document.getElementById('quiz-responses').value = selectedOption.nextElementSibling.value;
    document.getElementById('quiz-responses').dispatchEvent(new Event('change'));
};

/**
 * Allows to validate current selection for selected response.
 */
const submitResponseLocation = (event) => {
    const locateUrl = document.getElementById('validate-button').dataset.locateUrl;
    const parameters = {
        responseId: document.getElementById('quiz-responses').value,
        positionX: document.getElementById('selection-position-x').value,
        positionY: document.getElementById('selection-position-y').value,
        width: document.getElementById('selection-width').value,
        height: document.getElementById('selection-height').value
    };

    axios.post(locateUrl, parameters)
        .then((response) => {
            if (1 === response.data) {
                setLocationStateOK();
            } else {
                setLocationStateKO();
            }
        })
    ;
};

/**
 * Allows to display response location.
 */
const displayResponseLocation = (event) => {
    const locationUrl = event.target.dataset.locationUrl;
    const parameters = {id: event.target.value};

    axios.get(locationUrl, { params: parameters })
        .then((response) => {
            if (response.data.success) {
                const location = response.data.info;
                const canvas = document.getElementById('canvas');

                const locationElement = document.createElement('div');
                const left = (canvas.offsetWidth * location.positionX) / +canvas.dataset.pictureWidth;
                const top = (canvas.offsetHeight * location.positionY) / +canvas.dataset.pictureHeight;
                const width = (canvas.offsetWidth * location.width) / +canvas.dataset.pictureWidth;
                const height = (canvas.offsetHeight * location.height) / +canvas.dataset.pictureHeight;

                locationElement.classList.add('location');
                locationElement.style.top = `${top}px`;
                locationElement.style.left = `${left}px`;
                locationElement.style.width = `${width}px`;
                locationElement.style.height = `${height}px`;

                canvas.appendChild(locationElement);
                setLocationStateOK();

                setTimeout(
                        () => {locationElement.parentNode.removeChild(locationElement);},
                        2200
                );
            } else {
                clearLocationState();
            }
        })
    ;
};

const setLocationStateOK = () => {
    clearLocationState();
    document.getElementById('location-state').classList.add('has-text-success');
};

const setLocationStateKO = () => {
    clearLocationState();
    document.getElementById('location-state').classList.add('has-text-danger');
};

const clearLocationState = () => {
    document.getElementById('location-state').classList.remove('has-text-success');
    document.getElementById('location-state').classList.remove('has-text-danger');
};

document.getElementById('locate-responses') && initialize();
