import axios from 'axios';
import { displayModal } from './popup';

/**
 * Allows to display modal with a progress bar.
 *
 * @parma string progressUrl Url to call given new state of progress.
 */
export const displayProgressBar = (progressUrl) => {
    const progressBar = document.createElement('progress');
    progressBar.classList.add('progress', 'is-large');
    progressBar.setAttribute('value', 0);
    progressBar.setAttribute('max', 100);

    displayModal(progressBar);
    setTimeout(updateProgressBar, 500, progressBar, progressUrl);
};

/**
 * Allows to update progress bar with specified progressUrl.
 *
 * @parma Element progressBar Progress bar as DOM element to update.
 * @parma string progressUrl Url to call given new state of progress.
 */
const updateProgressBar = (progressBar, progressUrl) => {
    axios.get(progressUrl)
        .then(response => {
            const { current, total } = response.data;
            if (current > 0 && total > 0) {
                progressBar.setAttribute('max', total);
                progressBar.setAttribute('value', current);
            }
            if (current < total) {
                setTimeout(updateProgressBar, 700, progressBar, progressUrl);
            }
        })
    ;
};
