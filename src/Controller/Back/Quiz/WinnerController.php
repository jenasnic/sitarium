<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Winner;
use App\Enum\PagerEnum;
use App\Repository\Quiz\WinnerRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class WinnerController extends AbstractController
{
    /**
     * @Route("/admin/quiz/{quiz}/winner/list", requirements={"quiz": "\d+"}, name="bo_quiz_winner_list")
     */
    public function listWinnerAction(Request $request, WinnerRepository $winnerRepository, Quiz $quiz): Response
    {
        $page = intval($request->query->get('page', '1'));
        $name = $request->query->get('value', null);

        return $this->render('back/quiz/winner_list.html.twig', [
            'quiz' => $quiz,
            'pager' => $winnerRepository->getPagerForQuizId($quiz->getId(), $name, $page, PagerEnum::DEFAULT_MAX_PER_PAGE),
            'value' => $name,
        ]);
    }

    /**
     * @Route("/admin/quiz/winner/detail/{winner}", requirements={"winner": "\d+"}, name="bo_quiz_winner_detail")
     */
    public function detailWinnerAction(Winner $winner): Response
    {
        return $this->render('back/quiz/winner_view.html.twig', ['winner' => $winner]);
    }

    /**
     * @Route("/admin/quiz/{quiz}/winner/clear", requirements={"quiz": "\d+"}, name="bo_quiz_winner_clear")
     */
    public function clearWinnerAction(
        TranslatorInterface $translator,
        WinnerRepository $winnerRepository,
        Quiz $quiz
    ): Response {
        try {
            $winnerRepository->removeWinnersForQuizId($quiz->getId());
            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_quiz_list');
    }
}
