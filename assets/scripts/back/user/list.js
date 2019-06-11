import { initFilterAction } from '../filter';

/**
 * Allows to initialize actions on user list.
 */
const initActions = () => {
    initFilterAction();
};

document.getElementById('user-list') && initActions();
