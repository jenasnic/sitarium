import tingle from 'tingle.js';

/**
 * Allows to display popup for specified content an d
 */
export const displayPopup = (content, options) => {
    const defaultOptions = {
        closeLabel: 'Fermer',
        closeMethods: ['overlay', 'button', 'escape'],
    };

    const modal = new tingle.modal(Object.assign(defaultOptions, options));
    modal.setContent(content);
    modal.open();

    if (options.autoCloseDelay) {
        setTimeout(
            () => {
                if (modal.isOpen()) {
                    modal.close();
                }
            },
            options.autoCloseDelay
        );
    }
};
