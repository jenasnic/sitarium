import axios from 'axios';

let timeout = null;

const filterGrid = (url, value, callback) => {
    axios.get(url, {params: {value: value}})
        .then(response => {
            const gridResult = new DOMParser().parseFromString(response.data, 'text/html').getElementById('grid-wrapper');
            document.getElementById('grid-wrapper').innerHTML = gridResult.innerHTML;

            if (callback) {
                callback();
            }
        })
    ;
};

export const initFilterAction = (callback) => {
    document.querySelector('input[data-filter-url]').addEventListener('keyup', (event) => {
        if (timeout !== null) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function () {
            filterGrid(event.target.dataset.filterUrl, event.target.value, callback);
        }, 500);
    });
};
