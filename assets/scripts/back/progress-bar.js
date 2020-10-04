import axios from 'axios';
import { displayModal, closeModal } from './popup';

/**
 * Allows to display modal with a progress bar.
 *
 * @param string progressUrl Url to call given new state of progress.
 * @param function callback Callback to call after progress bar over.
 */
export const displayProgressBar = (progressUrl, callback) => {
    const progressBar = document.createElement('progress');
    progressBar.classList.add('progress', 'is-large');
    progressBar.setAttribute('value', 0);
    progressBar.setAttribute('max', 100);

    displayModal(progressBar);
    setTimeout(updateProgressBar, 500, progressBar, progressUrl, callback, true);
};

/**
 * Allows to activate a visible progress bar.
 *
 * @param Element progressBar Progress bar as DOM element to update.
 * @param string progressUrl Url to call given new state of progress.
 * @param function callback Callback to call after progress bar over.
 */
export const activateProgressBar = (progressBar, progressUrl, callback) => {
    setTimeout(updateProgressBar, 500, progressBar, progressUrl, callback, false);
};

/**
 * Allows to update progress bar with specified progressUrl.
 *
 * @param Element progressBar Progress bar as DOM element to update.
 * @param string progressUrl Url to call given new state of progress.
 * @param function callback Callback to call after progress bar over.
 * @param bool withModal TRUE if progress bar is displayed in a modal, FALSE either.
 */
const updateProgressBar = (progressBar, progressUrl, callback, withModal) => {
    axios.get(progressUrl)
        .then(response => {
            const { current, total, options } = response.data;
            progressBar.parentNode.dataset.info = options ? options : '';
            if (current > 0 && total > 0) {
                progressBar.setAttribute('max', total);
                progressBar.setAttribute('value', current);
            }
            if (current < total) {
                setTimeout(updateProgressBar, 700, progressBar, progressUrl, callback);
            } else {
                if (withModal) {
                    closeModal();
                }
                callback();
            }
        })
    ;
};
