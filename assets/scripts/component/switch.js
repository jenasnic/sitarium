
export const bindSwitchButton = (button, callback) => {
    const items = [...button.querySelectorAll('.switch-item')];

    items.forEach(itemAction => {
        itemAction.addEventListener('click', (event) => {
            if (event.target.classList.contains('active')) {
                return;
            }
            items.forEach((itemState) => {itemState.classList.remove('active');});
            itemAction.classList.add('active');
            callback(itemAction);
        });
    });
};
