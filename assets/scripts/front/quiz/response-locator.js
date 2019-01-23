
export default class ResponseLocator {
    pictureWidth;
    pictureHeight;
    locationDelay;
    locationBorderSize;

    /**
     * @param int pictureWidth Original picture width (used to calculate location).
     * @param int pictureHeight Original picture height (used to calculate location).
     * @param int locationDelay Time when showing response location on picture.
     * @param int locationBorderSize Size in pixel of border when locating response on picture.
     */
    constructor(pictureWidth, pictureHeight, locationDelay, locationBorderSize) {
        this.pictureWidth = pictureWidth;
        this.pictureHeight = pictureHeight;
        this.locationDelay = locationDelay;
        this.locationBorderSize = locationBorderSize;
    };

    /**
     * Allows to display response location on picture.
     * 
     * @param object responseLocation
     */
    displayResponseLocation(responseLocation) {
        const pictureQuiz = document.getElementById('picture-quiz');
        const locationElement = document.createElement('div');
        const left = ((pictureQuiz.offsetWidth * responseLocation.positionX) / this.pictureWidth) + pictureQuiz.offsetLeft;
        const top = ((pictureQuiz.offsetHeight * responseLocation.positionY) / this.pictureHeight) + pictureQuiz.offsetTop;
        const width = (pictureQuiz.offsetWidth * responseLocation.width) / this.pictureWidth;
        const height = (pictureQuiz.offsetHeight * responseLocation.height) / this.pictureHeight;

        locationElement.classList.add('location');
        locationElement.style.top = `${top - this.locationBorderSize}px`;
        locationElement.style.left = `${left - this.locationBorderSize}px`;
        locationElement.style.width = `${width}px`;
        locationElement.style.height = `${height}px`;

        document.getElementById('picture-wrapper').appendChild(locationElement);

        setTimeout(
            () => {locationElement.parentNode.removeChild(locationElement);},
            this.locationDelay
        );
    };
}
