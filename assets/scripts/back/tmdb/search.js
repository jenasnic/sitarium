import axios from 'axios';

/**
 * Allows to initiliaze action when searching maze item.
 */
const initActions = () => {
    const searchButton = document.getElementById('tmdb-search-button');
    const searchInput = document.getElementById('tmdb-search-input');
    const searchUrl = searchButton.dataset.searchUrl;

    searchInput.addEventListener('keyup', (event) => {
        const searchValue = event.target.value;
        if (searchValue.length > 2) {
            searchButton.disabled = false;
            if ('Enter' === event.key || 13 === event.keyCode) {
                searchTmdbItem(searchUrl, searchValue);
            }
        } else {
            searchButton.disabled = true;
        }
    });

    searchButton.addEventListener('click', (event) => {
        searchTmdbItem(searchUrl, searchInput.value);
    });

    searchInput.focus();
};

/**
 * Allows to display result list of TMDB items matching search.
 *
 * @param searchUrl Url to call using ajax to get result items matching search.
 * @param value Search value for TMDB items we are looking for.
 */
const searchTmdbItem = (searchUrl, value) => {
    axios.get(searchUrl, {params: {value: value}})
        .then(response => {
            document.getElementById('tmdb-search-result').innerHTML = response.data;
        })
    ;
};

document.getElementById('tmdb-search') && initActions();
