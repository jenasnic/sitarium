<?php

namespace App\Controller\Back\Maze;

use App\Domain\Command\Maze\AddMovieCommand;
use App\Entity\Maze\Movie;
use App\Repository\Maze\MovieRepository;
use App\Service\Handler\Maze\AddMovieHandler;
use App\Service\Tmdb\TmdbApiService;
use App\Validator\Maze\MovieValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends Controller
{
    /**
     * Max movie count to return when searching movies through TMDB.
     *
     * @var int
     */
    const MAX_MOVIE_RESULT_COUNT = 10;

    /**
     * @Route("/admin/maze/movie/list", name="bo_maze_movie_list")
     *
     * @param MovieRepository $movieRepository
     *
     * @return Response
     */
    public function listAction(MovieRepository $movieRepository): Response
    {
        return $this->render('back/maze/movie/list.html.twig', [
            'movies' => $movieRepository->findBy([], ['title' => 'asc']),
        ]);
    }

    /**
     * @Route("/admin/maze/movie/view/{movie}", requirements={"movie" = "\d+"}, name="bo_maze_movie_view")
     *
     * @param Movie $movie
     *
     * @return Response
     */
    public function viewAction(Movie $movie): Response
    {
        return $this->render('back/maze/movie/view.html.twig', ['movie' => $movie]);
    }

    /**
     * @Route("/admin/maze/movie/add/{tmdbId}", requirements={"tmdbId" = "\d+"}, defaults={"tmdbId" = 0}, name="bo_maze_movie_add")
     *
     * @param AddMovieHandler $handler
     * @param int $tmdbId
     *
     * @return Response
     */
    public function addAction(AddMovieHandler $handler, int $tmdbId): Response
    {
        // If no ID => allows user to search movie using TMDB API
        if (0 === $tmdbId) {
            return $this->render('back/maze/movie/add.html.twig');
        }

        try {
            $handler->handle(new AddMovieCommand($tmdbId));
            $this->addFlash('info', 'Le film a bien été ajouté à la liste');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'ajout');
        }

        return $this->redirectToRoute('bo_maze_movie_add');
    }

    /**
     * @Route("/admin/maze/movie/search/{title}", name="bo_maze_movie_search")
     *
     * @param TmdbApiService $tmdbService
     * @param string $name
     *
     * @return Response
     */
    public function searchAction(TmdbApiService $tmdbService, string $title): Response
    {
        $movies = [];

        if (strlen($title) > 2) {
            try {
                $result = $tmdbService->searchEntity(Movie::class, $title, new MovieValidator(), self::MAX_MOVIE_RESULT_COUNT);
                $movies = $result['results'];
            } catch (\Exception $e) {
                return new Response('Erreur lors de la recherche', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->render('back/maze/movie/search.html.twig', ['movies' => $movies]);
    }

    /**
     * @Route("/admin/maze/movie/delete/{movie}", requirements={"movie" = "\d+"}, name="bo_maze_movie_delete")
     *
     * @param EntityManagerInterface $entityManager
     * @param Movie $move
     *
     * @return Response
     */
    public function deleteAction(EntityManagerInterface $entityManager, Movie $movie): Response
    {
        try {
            $entityManager->remove($movie);
            $entityManager->flush();

            $this->addFlash('info', 'Suppression OK');
        } catch (\Exception $e) {
            $this->add('error', 'Erreur lors de la suppression');
        }

        return $this->redirectToRoute('bo_maze_movie_list');
    }
}
