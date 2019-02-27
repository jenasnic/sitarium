<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Winner;
use App\Repository\Quiz\WinnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class WinnerController extends AbstractController
{
    /**
     * @Route("/admin/quiz/{quiz}/winner/list", requirements={"quiz" = "\d+"}, name="bo_quiz_winner_list")
     *
     * @param WinnerRepository $winnerRepository
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function listWinnerAction(WinnerRepository $winnerRepository, Quiz $quiz): Response
    {
        return $this->render('back/quiz/winner_list.html.twig', [
            'quiz' => $quiz,
            'winners' => $winnerRepository->getWinnersForQuizId($quiz->getId()),
        ]);
    }

    /**
     * @Route("/admin/quiz/winner/detail/{winner}", requirements={"winner" = "\d+"}, name="bo_quiz_winner_detail")
     *
     * @param Winner $winner
     *
     * @return Response
     */
    public function detailWinnerAction(Winner $winner): Response
    {
        return $this->render('back/quiz/winner_view.html.twig', ['winner' => $winner]);
    }

    /**
     * @Route("/admin/quiz/{quiz}/winner/clear", requirements={"quiz" = "\d+"}, name="bo_quiz_winner_clear")
     *
     * @param TranslatorInterface $translator
     * @param WinnerRepository $winnerRepository
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function clearWinnerAction(
        TranslatorInterface $translator,
        WinnerRepository $winnerRepository,
        Quiz $quiz
    ): Response {
        try {
            $winnerRepository->removeWinnersForQuizId($quiz->getId());
            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_quiz_list');
    }
}
