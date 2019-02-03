import axios from 'axios';
import { displayModal } from '../popup';

/**
 * Allows to define action for statistics.
 *
 * @param form
 */
const initActions = () => {
    [...document.querySelectorAll('#statistics .tab-item')].forEach(
        (tab) => {
            tab.addEventListener('click', (event) => {
                activeTab(tab);
            });
        }
    );

    initDetailAction();
}

/**
 * Allows to initialize action to display detail about statistics.
 */
const initDetailAction = () => {
    [...document.querySelectorAll('.show-statistics-detail-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewStatisticsDetail(element.dataset.statisticsUrl);
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
    [...document.querySelectorAll('#statistics .tab-item')].forEach(
        (tab) => { tab.classList.remove('is-active'); }
    );
    currentTab.classList.add('is-active');
    displayPanel(document.getElementById(currentTab.dataset.panel));
};

/**
 * Allows to display specified panel and hide others.
 *
 * @param currentPanel
 */
const displayPanel = (currentPanel) => {
    [...document.querySelectorAll('#statistics .tab-panel')].forEach(
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
    axios.get(url)
        .then(response => {
            displayModal(response.data);
        })
    ;
};

document.getElementById('statistics') && initActions();
