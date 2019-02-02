<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\SessionValues;
use App\Service\Maze\ActorFilmographyBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FilmographyController extends Controller
{
    /**
     * @Route("/admin/maze/actor/filmography/build", name="bo_maze_actor_filmography_build", methods="POST")
     *
     * @param ActorFilmographyBuilder $filmographyBuilder
     *
     * @return JsonResponse
     */
    public function filmographyBuildAction(ActorFilmographyBuilder $filmographyBuilder): JsonResponse
    {
        try {
            $filmographyBuilder->build();

            return new JsonResponse(['success' => true, 'message' => 'Construction de la filmographie OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la construction de la filmographie']);
        }
    }

    /**
     * @Route("/admin/maze/actor/filmography/progress", name="bo_maze_actor_filmography_progress")
     *
     * @param SessionInterface $session
     *
     * @return JsonResponse
     */
    public function filmographyProgressAction(SessionInterface $session): JsonResponse
    {
        return new JsonResponse([
            'current' => $session->get(SessionValues::SESSION_BUILD_FILMOGRAPHY_PROGRESS),
            'total' => $session->get(SessionValues::SESSION_BUILD_FILMOGRAPHY_TOTAL),
        ]);
    }
}
