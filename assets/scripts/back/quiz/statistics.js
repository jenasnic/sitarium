import { displayModal, closeModal } from "../popup";

/**
 * Allows to define action when clicking on tabs
 *
 * @param form
 */
const initTabAction = () => {
    document.querySelectorAll('#statistics-tabs .tab-item').forEach(
        (tab) => {
            tab.addEventListener('click', (event) => {
                activeTab(tab);
            });
        }
    );
}

/**
 * Allows to initialize action to display detail about statistics.
 */
const initDetailAction = () => {
    document.querySelectorAll('.show-statistics-detail-button').forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewStatisticsDetail(element.getAttribute('data-url'));
            });
        }
    );
};

/**
 * Allows to activate specified tab item and deactivate others.
 * NOTE : this methode display panel linked to tab.
 *
 * @param currentTab
 */
const activeTab = (currentTab) => {
    document.querySelectorAll('#statistics-tabs .tab-item').forEach(
        (tab) => {tab.classList.remove('is-active')}
    );
    currentTab.classList.add('is-active');
    displayPanel(document.getElementById(currentTab.getAttribute('data-panel')));
};

/**
 * Allows to display specified panel and hide others.
 *
 * @param currentPanel
 */
const displayPanel = (currentPanel) => {
    document.querySelectorAll('#statistics-tabs .tab-panel').forEach(
        (panel) => {panel.classList.add('is-hidden')}
    );
    currentPanel.classList.remove('is-hidden');
};

/**
 * Allows to display statistics detail in popup.
 *
 * @param url Url to call to get detail.
 */
const viewStatisticsDetail = (url) => {
    fetch(url, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            displayModal('quiz-statistics-detail-modal', response);
        })
    ;
};

document.getElementById('statistics-tabs') && initTabAction();

document.getElementById('quiz-statistics-detail-modal') && initDetailAction();
