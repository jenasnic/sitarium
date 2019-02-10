<?php

namespace App\Controller\Back\Tmdb;

use App\Model\Tmdb\Movie;
use App\Model\Tmdb\Actor;
use App\Service\Tmdb\TmdbApiService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayController extends Controller
{
    /**
     * @Route("/admin/tmdb/display/actor/{tmdbId}", name="bo_tmdb_display_actor", requirements={"tmdbId"="\d+"})
     *
     * @param Request $request
     * @param TmdbApiService $tmdbService
     * @param int $tmdbId
     *
     * @return Response
     */
    public function displayActorAction(Request $request, TmdbApiService $tmdbService, int $tmdbId): Response
    {
        return $this->render('back/tmdb/display/actor.html.twig', [
            'actor' => $tmdbService->getEntity(Actor::class, $tmdbId),
        ]);
    }

    /**
     * @Route("/admin/tmdb/display/movie/{tmdbId}", name="bo_tmdb_display_movie", requirements={"tmdbId"="\d+"})
     *
     * @param Request $request
     * @param TmdbApiService $tmdbService
     * @param int $tmdbId
     *
     * @return Response
     */
    public function displayMovieAction(Request $request, TmdbApiService $tmdbService, int $tmdbId): Response
    {
        return $this->render('back/tmdb/display/movie.html.twig', [
            'movie' => $tmdbService->getEntity(Movie::class, $tmdbId),
        ]);
    }
}
