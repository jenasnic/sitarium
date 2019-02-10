<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\SessionValues;
use App\Service\Maze\ActorFilmographyBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FilmographyController extends Controller
{
    /**
     * @Route("/admin/maze/actor/filmography/build", name="bo_maze_actor_filmography_build", methods="POST")
     *
     * @param TranslatorInterface $translator
     * @param ActorFilmographyBuilder $filmographyBuilder
     *
     * @return JsonResponse
     */
    public function filmographyBuildAction(TranslatorInterface $translator, ActorFilmographyBuilder $filmographyBuilder): JsonResponse
    {
        try {
            $filmographyBuilder->build();

            return new JsonResponse(['success' => true, 'message' => $translator->trans('back.maze.filmography.success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('back.maze.filmography.error')]);
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
