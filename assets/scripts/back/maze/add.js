import axios from 'axios';

/**
 * Allows to initiliaze action when searching maze item.
 */
const initActions = () => {
    const addButton = document.getElementById('maze-item-add-button');
    const searchUrl = addButton.dataset.searchUrl;

    document.getElementById('maze-item-add-search').addEventListener('keyup', (event) => {
        const searchValue = event.target.value;
        if (searchValue.length > 2) {
            addButton.disabled = false;
            if ('Enter' === event.key || 13 === event.keyCode) {
                searchMazeItem(searchUrl, searchValue);
            }
        } else {
            addButton.disabled = true;
        }
    });

    addButton.addEventListener('click', (event) => {
        searchMazeItem(searchUrl, document.getElementById('maze-item-add-search').value);
    });
};

/**
 * Allows to display result list of maze items matching search.
 *
 * @param searchUrl Url to call using ajax to get maze items matching search.
 * @param value Search value for maze item we are looking for.
 */
const searchMazeItem = (searchUrl, value) => {
    axios.get(searchUrl, {params: {value: value}})
        .then(response => {
            document.getElementById('maze-item-add-result').innerHTML = response.data;
        })
    ;
};

document.getElementById('maze-item-add') && initActions();
