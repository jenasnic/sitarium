
/**
 * Allows to display modal with specific content.
 *
 * @param string id identifier of modal to display.
 * @param string content text (or HTML content) to display in modal or null if no sepcific content.
 */
export const displayModalWithContent = (id, content = null) => {
    const modal = document.querySelector('#' + id);
    if (null !== content) {
        modal.querySelector('.modal-card-body').innerHTML = content;
    }
    modal.classList.add('is-active');
};

/**
 * Allows to close modal.
 *
 * @param string id identifier of modal to close.
 */
export const closeModal = (id) => {
    document.querySelector('#' + id).classList.remove('is-active');
};

/**
 * Define action to close modal when clicking on background or on button to close.
 * NOTE : action allowed only for modal that define close button.
 */
document.querySelector('.modal') && document.querySelectorAll('.modal').forEach(function(modalElement) {
    modalElement.querySelector('.modal-close') 
        && modalElement.querySelectorAll('.modal-background, .modal-close').forEach(function(closeItem) {
            closeItem.addEventListener('click', function() {
                modalElement.classList.remove('is-active');
            });
        });
});
