import axios from 'axios';
import { displayModal, closeModal } from '../popup';

/**
 * Allows to initialize action to display detail about movie.
 */
const initActions = () => {
    [...document.querySelectorAll('.show-tagline-movie-detail-button')].forEach(
        (element) => {
            element.addEventListener('click', (event) => {
                viewMovieDetail(element.dataset.detailUrl);
            });
        }
    );
};

/**
 * Allows to display movie informations in popup.
 * 
 * @param detailUrl Url to call to get detail.
 */
const viewMovieDetail = (detailUrl) => {
    axios.get(detailUrl)
        .then(response => {
            displayModal(response.data);
        })
    ;
};

document.getElementById('tagline-movie-list') && initActions();
