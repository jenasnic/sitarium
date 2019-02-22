
/**
 * Allows to submit tagline form with clicked genre.
 */
const initActions = () => {
    const taglineForm = document.getElementById('tagline-selection-form');

    [...document.querySelectorAll('#genre-wrapper .button')].forEach((button) => {
        button.addEventListener('click', (event) => {
            taglineForm.action = button.dataset.actionUrl;
            taglineForm.submit();
        });
    });
}

document.getElementById('tagline-home') && initActions();
