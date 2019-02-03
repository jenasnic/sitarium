<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Enum\Quiz\SessionValues;
use App\Service\Quiz\TmdbLinkBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TmdbLinkController extends Controller
{
    /**
     * @Route("/admin/quiz/tmdb-link/{quiz}", requirements={"quiz" = "\d+"}, name="bo_quiz_link_tmdb_start", methods="POST")
     *
     * @param TmdbLinkBuilder $tmdbLinkBuilder
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function tmdbLinkAction(TmdbLinkBuilder $tmdbLinkBuilder, Quiz $quiz): JsonResponse
    {
        try {
            $tmdbLinkBuilder->build($quiz->getId());

            return new JsonResponse(['success' => true, 'message' => 'Création des liens TMDB OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la création des liens TMDB']);
        }
    }

    /**
     * @Route("/admin/quiz/tmdb-link/step", name="bo_quiz_link_tmdb_progress")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tmdbLinkStepAction(Request $request): JsonResponse
    {
        return new JsonResponse([
            'current' => $request->getSession()->get(SessionValues::SESSION_BUILD_TMDB_LINK_PROGRESS),
            'total' => $request->getSession()->get(SessionValues::SESSION_BUILD_TMDB_LINK_TOTAL),
        ]);
    }
}
