
// Define action when user fill search field
document.getElementById('movie-add')
    && document.getElementById('movie-add-search').addEventListener('keyup', function(event) {
        const movie = document.getElementById('movie-add-search').value;
        if (movie.length >= 2) {
            document.getElementById('movie-add-button').disabled = false;
            if (event.keyCode == 13) {
                searchMovie(movie);
            }
        } else {
            document.getElementById('movie-add-button').disabled = true;
        }
    });

// Define action when user click on search button to add an movie
document.getElementById('movie-add')
    && document.getElementById('movie-add-button').addEventListener('click', function(event) {
        searchMovie(document.getElementById('movie-add-search').value);
    });

/**
 * Allows to display result list of movies matching search.
 * 
 * @param movie Title of movie we are looking for.
 */
const searchMovie = (movie) => {
    fetch(`/admin/maze/movie/search/${movie}`, {credentials: 'same-origin'})
        .then(response => response.text())
        .then(response => {
            document.getElementById('movie-add-result').innerHTML = response;
        });
};
