import { displayPopup } from '../../component/popup';

let selectedCount = document.querySelectorAll('.selection-item.active').length;

/**
 * Allows to initialize action for max path selection (for actors and movies).
 *
 * @param int selectionCount Number of items to select
 */
const initActions = (selectionCount) => {
    const button = document.getElementById('selection-submit');

    [...document.querySelectorAll('.selection-item')].forEach((item) => {
        item.addEventListener('click', (event) => {
            toggleItem(item, selectionCount);
            toggleButton(button, selectionCount);
        });
    });
};

/**
 * Allows to select/unselect maze item for max path.
 *
 * @param Element item
 * @param int selectionCount Number of items to select
 */
const toggleItem = (item, selectionCount) => {
    if (selectedCount < selectionCount || item.classList.contains('active')) {
        item.classList.toggle('active');
        selectedCount = document.querySelectorAll('.selection-item.active').length;
    } else {
        displayPopup(`Vous ne pouvez pas sélectionner plus de ${selectionCount} éléments !`, {});
    }
};

/**
 * Allows to enabled/disabled submit button for max path.
 *
 * @param Element button
 * @param int selectionCount Number of items to select
 */
const toggleButton = (button, selectionCount) => {
    button.disabled = (selectedCount < selectionCount);
};

document.getElementById('maze-max-path') && initActions(10);
document.getElementById('maze-min-path') && initActions(2);
