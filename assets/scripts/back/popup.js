
/**
 * Allows to display modal with specific text/html content or DOM element.
 *
 * @param string|Element content Text or DOM element to display in modal.
 */
export const displayModal = (content) => {
    const modal = document.getElementById('popup');
    if ('string' === typeof(content)) {
        modal.querySelector('.modal-content').innerHTML = content;
    } else {
        modal.querySelector('.modal-content').innerHTML = '';
        modal.querySelector('.modal-content').appendChild(content);
    }
    modal.classList.add('is-active');
};

/**
 * Allows to close modal.
 *
 * @param string id identifier of modal to close.
 */
export const closeModal = () => {
    document.getElementById('popup').classList.remove('is-active');
};

/**
 * Define action to close modal when clicking on background or on button to close.
 * NOTE : action allowed only for modal that define close button.
 */
document.querySelector('.modal') && [...document.querySelectorAll('.modal')].forEach(function(modalElement) {
    modalElement.querySelector('.modal-close') 
        && [...modalElement.querySelectorAll('.modal-background, .modal-close')].forEach(function(closeItem) {
            closeItem.addEventListener('click', function() {
                modalElement.classList.remove('is-active');
            });
        });
});
