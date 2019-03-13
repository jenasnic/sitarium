<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\SessionValueEnum;
use App\Service\Maze\MovieCastingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CastingController extends AbstractController
{
    /**
     * @Route("/admin/maze/movie/casting/build", name="bo_maze_movie_casting_build", methods="POST")
     *
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param MovieCastingBuilder $castingBuilder
     *
     * @return JsonResponse
     */
    public function castingBuildAction(
        SessionInterface $session,
        TranslatorInterface $translator,
        MovieCastingBuilder $castingBuilder
    ): JsonResponse {
        if ($session->has(SessionValueEnum::SESSION_BUILD_CASTING_PROGRESS)) {
            return new JsonResponse(['status' => 'progress']);
        }

        try {
            $castingBuilder->build();
            $this->addFlash('info', $translator->trans('back.maze.casting.success'));

            return new JsonResponse(['status' => 'over']);
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.maze.casting.error'));

            return new JsonResponse(['status' => 'error']);
        }
    }

    /**
     * @Route("/admin/maze/movie/casting/progress", name="bo_maze_movie_casting_progress")
     *
     * @param SessionInterface $session
     *
     * @return JsonResponse
     */
    public function castingProgressAction(SessionInterface $session): JsonResponse
    {
        return new JsonResponse([
            'current' => $session->get(SessionValueEnum::SESSION_BUILD_CASTING_PROGRESS, 0),
            'total' => $session->get(SessionValueEnum::SESSION_BUILD_CASTING_TOTAL, 0),
        ]);
    }
}
