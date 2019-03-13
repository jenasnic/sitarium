<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\SessionValueEnum;
use App\Service\Maze\ActorFilmographyBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FilmographyController extends AbstractController
{
    /**
     * @Route("/admin/maze/actor/filmography/build", name="bo_maze_actor_filmography_build", methods="POST")
     *
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param ActorFilmographyBuilder $filmographyBuilder
     *
     * @return JsonResponse
     */
    public function filmographyBuildAction(
        SessionInterface $session,
        TranslatorInterface $translator,
        ActorFilmographyBuilder $filmographyBuilder
    ): JsonResponse {
        if ($session->has(SessionValueEnum::SESSION_BUILD_FILMOGRAPHY_PROGRESS)) {
            return new JsonResponse(['status' => 'progress']);
        }

        try {
            $filmographyBuilder->build();
            $this->addFlash('info', $translator->trans('back.maze.filmography.success'));

            return new JsonResponse(['status' => 'over']);
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.maze.filmography.error'));

            return new JsonResponse(['status' => 'error']);
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
            'current' => $session->get(SessionValueEnum::SESSION_BUILD_FILMOGRAPHY_PROGRESS, 0),
            'total' => $session->get(SessionValueEnum::SESSION_BUILD_FILMOGRAPHY_TOTAL, 0),
        ]);
    }
}
