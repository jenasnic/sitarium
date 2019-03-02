
/**
 * Define action to display/hide menu with mobile design.
 */
const initMobileMenuAction = () => {
    document.getElementById('menu-entry').addEventListener('click', (event) => {
        document.getElementById('menu-content').classList.toggle('active');
    });
}

document.getElementById('header') && initMobileMenuAction();
