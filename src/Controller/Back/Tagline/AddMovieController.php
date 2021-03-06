<?php

namespace App\Controller\Back\Tagline;

use App\Domain\Command\Tagline\AddMoviesCommand;
use App\Enum\Tmdb\TypeEnum;
use App\Service\Handler\Tagline\AddMoviesHandler;
use App\Service\Tmdb\DisplayableResultAdapter;
use App\Service\Tmdb\TmdbDataProvider;
use App\Validator\Tmdb\TaglineMovieValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddMovieController extends AbstractController
{
    /**
     * @Route("/admin/tagline/movie/new", name="bo_tagline_movie_new")
     */
    public function newAction(UrlGeneratorInterface $urlGenerator): Response
    {
        return $this->render('back/tmdb/search.html.twig', [
            'type' => TypeEnum::MOVIE,
            'searchUrl' => $urlGenerator->generate('bo_tagline_movie_search'),
        ]);
    }

    /**
     * @Route("/admin/tagline/movie/search", name="bo_tagline_movie_search")
     */
    public function searchAction(
        Request $request,
        TmdbDataProvider $tmdbDataProvider,
        DisplayableResultAdapter $displayableResultAdapter
    ): Response {
        $value = $request->query->get('value', '');
        $movies = [];

        if (strlen($value) > 2) {
            $movies = $tmdbDataProvider->searchMovies($value, new TaglineMovieValidator());
        }

        return $this->render('back/tmdb/search_result.html.twig', [
            'items' => $displayableResultAdapter->adaptArray($movies),
            'callback' => 'bo_tagline_movie_similar',
        ]);
    }

    /**
     * @Route("/admin/tagline/movie/similar/{tmdbId}", requirements={"tmdbId": "\d+"}, name="bo_tagline_movie_similar")
     */
    public function similarAction(
        TmdbDataProvider $tmdbDataProvider,
        int $tmdbId
    ): Response {
        return $this->render('back/tagline/movie/similar.html.twig', [
            'movie' => $tmdbDataProvider->getMovie($tmdbId),
            'movies' => $tmdbDataProvider->getSimilarMovies($tmdbId),
        ]);
    }

    /**
     * @Route("/admin/tagline/movie/add-selection", name="bo_tagline_movie_add_selection", methods="POST")
     */
    public function addSelectionAction(
        Request $request,
        TranslatorInterface $translator,
        AddMoviesHandler $handler
    ): Response {
        try {
            /** @var array<int> $tmdbIds */
            $tmdbIds = explode(',', $request->request->get('movie-selection-ids'));
            $handler->handle(new AddMoviesCommand($tmdbIds));
            $this->addFlash('info', $translator->trans('back.global.add.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.add.error'));
        }

        return $this->redirectToRoute('bo_tagline_movie_list');
    }
}
