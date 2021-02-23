<?php

namespace App\Controller\Back\Tmdb;

use App\Service\Tmdb\TmdbDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayController extends AbstractController
{
    /**
     * @Route("/admin/tmdb/display/actor/{tmdbId}", name="bo_tmdb_display_actor", requirements={"tmdbId"="\d+"})
     */
    public function displayActorAction(Request $request, TmdbDataProvider $tmdbDataProvider, int $tmdbId): Response
    {
        return $this->render('back/tmdb/display/actor.html.twig', [
            'actor' => $tmdbDataProvider->getActor($tmdbId),
        ]);
    }

    /**
     * @Route("/admin/tmdb/display/movie/{tmdbId}", name="bo_tmdb_display_movie", requirements={"tmdbId"="\d+"})
     */
    public function displayMovieAction(Request $request, TmdbDataProvider $tmdbDataProvider, int $tmdbId): Response
    {
        return $this->render('back/tmdb/display/movie.html.twig', [
            'movie' => $tmdbDataProvider->getMovie($tmdbId),
        ]);
    }
}
