
// Define action when user fill search field
document.getElementById('actor-add')
    && document.getElementById('actor-add-search').addEventListener('keyup', function(event) {
        const actor = document.getElementById('actor-add-search').value;
        if (actor.length >= 2) {
            document.getElementById('actor-add-button').disabled = false;
            if (event.keyCode == 13) {
                searchActor(actor);
            }
        } else {
            document.getElementById('actor-add-button').disabled = true;
        }
    });

// Define action when user click on search button to add an actor
document.getElementById('actor-add')
    && document.getElementById('actor-add-button').addEventListener('click', function(event) {
        searchActor(document.getElementById('actor-add-search').value);
    });

/**
 * Allows to display result list of actors matching search.
 * 
 * @param actor Name of actor we are looking for.
 */
const searchActor = (actor) => {
    fetch(`/admin/maze/actor/search/${actor}`, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            document.getElementById('actor-add-result').innerHTML = response;
        });
};
