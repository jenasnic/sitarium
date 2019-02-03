<?php

namespace App\Controller\Back\Quiz;

use App\Domain\Command\Quiz\ReorderQuizCommand;
use App\Repository\Quiz\QuizRepository;
use App\Service\Handler\Quiz\ReorderQuizHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizListController extends Controller
{
    /**
     * @Route("/admin/quiz/list", name="bo_quiz_list")
     *
     * @param QuizRepository $quizRepository
     *
     * @return Response
     */
    public function listAction(QuizRepository $quizRepository): Response
    {
        return $this->render('back/quiz/list.html.twig', [
            'quizs' => $quizRepository->findBy([], ['rank' => 'asc']),
        ]);
    }

    /**
     * @Route("/admin/quiz/reorder", name="bo_quiz_reorder", methods="POST")
     *
     * @param Request $request
     * @param ReorderQuizHandler $handler
     *
     * @return Response
     */
    public function reorderAction(Request $request, ReorderQuizHandler $handler): Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $reorderedIds = json_decode($data['reorderedIds']);

            $handler->handle(new ReorderQuizCommand($reorderedIds));

            return new JsonResponse(['success' => true, 'message' => 'Réordonnancement des quiz OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors du réordonnancement des quiz']);
        }
    }
}
