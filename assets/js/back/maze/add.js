
/**
 * Allows to display result list of maze items matching search.
 *
 * @param baseUrl Url to call using ajax to get maze items matching search.
 * @param value Search value for maze item we are looking for.
 */
const searchMazeItem = (baseUrl, value) => {
    const searchUrl = baseUrl + value;
    fetch(searchUrl, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            document.getElementById('maze-item-add-result').innerHTML = response;
        });
};

/**
 * Allows to initiliaze action when searching maze item.
 */
const initAddAction = () => {
    const baseUrl = document.getElementById('maze-item-add').getAttribute('data-base-url');
    const addButton = document.getElementById('maze-item-add-button');

    // Define action when user fill search field
    document.getElementById('maze-item-add-search').addEventListener('keyup', function(event) {
        const searchValue = event.target.value;
        if (searchValue.length > 2) {
            addButton.disabled = false;
            if (event.keyCode == 13) {
                searchMazeItem(baseUrl, searchValue);
            }
        } else {
            addButton.disabled = true;
        }
    });

    // Define action when user click on search button to add an item
    addButton.addEventListener('click', function(event) {
        searchMazeItem(baseUrl, document.getElementById('maze-item-add-search').value);
    });
};

document.getElementById('maze-item-add') && initAddAction();
