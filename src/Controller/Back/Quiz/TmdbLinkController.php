<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Enum\Quiz\SessionValues;
use App\Service\Quiz\TmdbLinkBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TmdbLinkController extends AbstractController
{
    /**
     * @Route("/admin/quiz/tmdb-link/{quiz}", requirements={"quiz" = "\d+"}, name="bo_quiz_link_tmdb_start", methods="POST")
     *
     * @param TranslatorInterface $translator
     * @param TmdbLinkBuilder $tmdbLinkBuilder
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function tmdbLinkAction(
        TranslatorInterface $translator,
        TmdbLinkBuilder $tmdbLinkBuilder,
        Quiz $quiz
    ): JsonResponse {
        try {
            $tmdbLinkBuilder->build($quiz->getId());

            return new JsonResponse(['success' => true, 'message' => $translator->trans('back.quiz.tmdb_link.success')]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('back.quiz.tmdb_link.error')]);
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
