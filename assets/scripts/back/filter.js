import axios from 'axios';

let timeout = null;

const filterGrid = (url, value) => {
    axios.get(url, {params: {value: value}})
        .then(response => {
            const gridResult = new DOMParser().parseFromString(response.data, 'text/html').getElementById('grid-wrapper');
            document.getElementById('grid-wrapper').innerHTML = gridResult.innerHTML;
        })
    ;
};

[...document.querySelectorAll('input[data-filter-url]')].forEach((input) => {
    input.addEventListener('keyup', (event) => {
        if (timeout !== null) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function () {
            filterGrid(event.target.dataset.filterUrl, event.target.value);
        }, 500);
    });
});
