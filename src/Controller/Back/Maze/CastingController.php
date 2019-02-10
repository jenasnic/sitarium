<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\SessionValues;
use App\Service\Maze\MovieCastingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CastingController extends Controller
{
    /**
     * @Route("/admin/maze/movie/casting/build", name="bo_maze_movie_casting_build", methods="POST")
     *
     * @param TranslatorInterface $translator
     * @param MovieCastingBuilder $castingBuilder
     *
     * @return JsonResponse
     */
    public function castingBuildAction(TranslatorInterface $translator, MovieCastingBuilder $castingBuilder): JsonResponse
    {
        try {
            $castingBuilder->build();

            return new JsonResponse(['success' => true, 'message' => $translator->trans('back.maze.casting.success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('back.maze.casting.error')]);
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
            'current' => $session->get(SessionValues::SESSION_BUILD_CASTING_PROGRESS),
            'total' => $session->get(SessionValues::SESSION_BUILD_CASTING_TOTAL),
        ]);
    }
}
