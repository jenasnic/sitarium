
/**
 * Define action to display/hide menu with burger icon.
 */
const initBurgerMenuAction = () => {
    document.querySelector('#header .burger-icon').addEventListener('click', (event) => {
        document.querySelector('#menu').classList.toggle('active');
    });
}

document.getElementById('header') && initBurgerMenuAction();
