<?php

namespace App\Controller\Back\Maze;

use App\Domain\Command\Maze\AddMovieCommand;
use App\Entity\Maze\Movie;
use App\Enum\PagerEnum;
use App\Enum\Tmdb\TypeEnum;
use App\Repository\Maze\MovieRepository;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Handler\Maze\AddMovieHandler;
use App\Service\Tmdb\TmdbApiService;
use App\Validator\Maze\MovieValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MovieController extends AbstractController
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
     * @param Request $request
     * @param MovieRepository $movieRepository
     * @param BuildProcessRepository $buildProcessRepository
     *
     * @return Response
     */
    public function listAction(
        Request $request,
        MovieRepository $movieRepository,
        BuildProcessRepository $buildProcessRepository
    ): Response {
        $page = $request->query->get('page', 1);
        $title = $request->query->get('value', null);

        return $this->render('back/maze/movie/list.html.twig', [
            'pager' => $movieRepository->getPager($title, $page, PagerEnum::DEFAULT_MAX_PER_PAGE),
            'value' => $title,
            'pendingProcess' => $buildProcessRepository->findPendingProcess(),
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
     * @Route("/admin/maze/movie/new", name="bo_maze_movie_new")
     *
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @return Response
     */
    public function newAction(UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('back/tmdb/search.html.twig', [
            'type' => TypeEnum::MOVIE,
            'searchUrl' => $urlGenerator->generate('bo_maze_movie_search'),
        ]);
    }

    /**
     * @Route("/admin/maze/movie/add/{tmdbId}", requirements={"tmdbId" = "\d+"}, name="bo_maze_movie_add")
     *
     * @param TranslatorInterface $translator
     * @param AddMovieHandler $handler
     * @param int $tmdbId
     *
     * @return Response
     */
    public function addAction(
        TranslatorInterface $translator,
        AddMovieHandler $handler,
        int $tmdbId
    ): Response {
        try {
            $handler->handle(new AddMovieCommand($tmdbId));
            $this->addFlash('info', $translator->trans('back.global.add.success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.add.error'));
        }

        return $this->redirectToRoute('bo_maze_movie_new');
    }

    /**
     * @Route("/admin/maze/movie/search", name="bo_maze_movie_search")
     *
     * @param Request $request
     * @param TmdbApiService $tmdbService
     *
     * @return Response
     */
    public function searchAction(
        Request $request,
        TmdbApiService $tmdbService
    ): Response {
        $title = $request->query->get('value', '');
        $movies = [];

        if (strlen($title) > 2) {
            $result = $tmdbService->searchEntity(Movie::class, $title, new MovieValidator(), self::MAX_MOVIE_RESULT_COUNT);
            $movies = $result['results'];
        }

        return $this->render('back/tmdb/search_result.html.twig', [
            'items' => $movies,
            'callback' => 'bo_maze_movie_add',
        ]);
    }

    /**
     * @Route("/admin/maze/movie/delete/{movie}", requirements={"movie" = "\d+"}, name="bo_maze_movie_delete")
     *
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param Movie $movie
     *
     * @return Response
     */
    public function deleteAction(
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        Movie $movie
    ): Response {
        try {
            $entityManager->remove($movie);
            $entityManager->flush();

            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_maze_movie_list');
    }
}
