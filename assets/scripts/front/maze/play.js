import PerfectScrollbar from 'perfect-scrollbar';

const initMaze = (options) => {
    initScroll(options.itemWidth);
    initHelper();
}

/**
 * Allows to initialize scrollbar.
 *
 * @param int itemWidth Width of items in scrollable element (used to calculate total width for scrollable element).
 */
const initScroll = (itemWidth) => {
    const scrollWrapper = document.getElementById('scroll-wrapper');
    const itemCount = scrollWrapper.querySelectorAll('.maze-item').length;
    const width = itemWidth * itemCount;

    document.getElementById('maze-items').style.width = `${width}px`;
    new PerfectScrollbar(scrollWrapper);
};

/**
 * Allows to show/hide helper area.
 */
const initHelper = () => {
    document.getElementById('toggle-button').addEventListener('click', (event) => {
        event.target.classList.toggle('fa-plus-circle');
        event.target.classList.toggle('fa-minus-circle');
        document.getElementById('toggle-wrapper').classList.toggle('active');
    });
}

document.getElementById('maze-play') && initMaze({itemWidth: 224});
