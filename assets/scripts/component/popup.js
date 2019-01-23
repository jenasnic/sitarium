import tingle from 'tingle.js';

/**
 * Allows to display popup for specified content with Tingle options.
 * Additionnal option 'autoCloseDelay' is available to close modal after specific delay (in ms).
 */
export const displayPopup = (content, options) => {
    const defaultOptions = {
        closeLabel: 'Fermer',
        closeMethods: ['overlay', 'button', 'escape'],
        ...options
    };

    const modal = new tingle.modal(defaultOptions);
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

/**
 * Allows to display form popup with both buttons 'Validate' and 'Cancel'.
 * Additional options are 'validateLabel', 'cancelLabel', 'validateCallback'.
 * NOTE : if 'cancelLabel' is false, button won't be added.
 */
export const displayFormPopup = (content, options, callback) => {
    const defaultOptions = {
        footer: true,
        closeLabel: 'Fermer',
        closeMethods: ['button'],
        ...options,
    };

    const modal = new tingle.modal(defaultOptions);
    modal.setContent(content);

    if (null === options.cancelLabel || options.cancelLabel !== false) {
        const cancelLabel = options.cancelLabel ? options.cancelLabel : 'Cancel';
        modal.addFooterBtn(cancelLabel, 'button cancel', () => {
            modal.close();
        });
    }

    const validateLabel = options.validateLabel ? validateLabel : 'OK';
    modal.addFooterBtn(validateLabel, 'button valid', () => {
        callback(modal);
    });

    modal.open();
};
