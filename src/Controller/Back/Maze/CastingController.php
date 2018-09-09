<?php

namespace App\Controller\Back\Maze;

use App\Enum\Maze\SessionValues;
use App\Service\Maze\MovieCastingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CastingController extends Controller
{
    /**
     * @Route("/admin/maze/movie/casting/build", name="bo_maze_movie_casting_build")
     *
     * @param MovieCastingBuilder $castingBuilder
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function castingBuildAction(MovieCastingBuilder $castingBuilder)
    {
        try {
            $castingBuilder->build();

            return new JsonResponse(['success' => true, 'message' => 'Construction du casting OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la construction du casting']);
        }
    }

    /**
     * @Route("/admin/maze/movie/casting/progress", name="bo_maze_movie_casting_progress")
     *
     * @param SessionInterface $session
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function castingProgressAction(SessionInterface $session)
    {
        return new JsonResponse([
            'current' => $session->get(SessionValues::SESSION_BUILD_CASTING_PROGRESS),
            'total' => $session->get(SessionValues::SESSION_BUILD_CASTING_TOTAL)
        ]);
    }
}
