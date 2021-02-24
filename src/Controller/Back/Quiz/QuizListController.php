<?php

namespace App\Controller\Back\Quiz;

use App\Domain\Command\Quiz\ReorderQuizCommand;
use App\Repository\Quiz\QuizRepository;
use App\Service\Handler\Quiz\ReorderQuizHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuizListController extends AbstractController
{
    /**
     * @Route("/admin/quiz/list", name="bo_quiz_list")
     */
    public function listAction(QuizRepository $quizRepository): Response
    {
        return $this->render('back/quiz/list.html.twig', [
            'quizs' => $quizRepository->findBy([], ['rank' => 'asc']),
        ]);
    }

    /**
     * @Route("/admin/quiz/reorder", name="bo_quiz_reorder", methods="POST")
     */
    public function reorderAction(
        Request $request,
        TranslatorInterface $translator,
        ReorderQuizHandler $handler
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            $reorderedIds = json_decode($data['reorderedIds'], true);

            $handler->handle(new ReorderQuizCommand($reorderedIds));

            return new JsonResponse(['success' => true, 'message' => $translator->trans('back.global.order.success')]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('back.global.order.error')]);
        }
    }
}
