
/**
 * Allows to get offset left/top for specified element.
 *
 * @param element
 *
 * @return object with left and top
 */
export const getOffsetElement = (element) => {
    const offset = {
        left: 0,
        top: 0
    }

    while (element.offsetParent) {
        offset.left += element.offsetLeft;
        offset.top += element.offsetTop;
        element = element.offsetParent;
    }

    return offset;
}

/**
 * Allows to get position for an event relative to its parent element.
 *
 * @param Event event
 * @param bool isMobile TRUE for event from mobile (touchstart), FALSE either (click)
 *
 * @return object with positionX and positionY
 */
export const getRelativePosition = (event, isMobile) => {
    const posX = isMobile ? event.touches[0].clientX : event.clientX;
    const posY = isMobile ? event.touches[0].clientY : event.clientY;

    const position = {
        positionX: posX + document.body.scrollLeft + document.documentElement.scrollLeft,
        positionY: posY + document.body.scrollTop + document.documentElement.scrollTop
    };

    const offsetToRemove = getOffsetElement(event.target);
    position.positionX -= offsetToRemove.left;
    position.positionY -= offsetToRemove.top;

    return position;
}
