<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\RegisterWinnerCommand;
use App\Entity\Quiz\Quiz;
use App\Service\Handler\Quiz\RegisterWinnerHandler;
use App\Service\Quiz\ResolveQuizValidator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WinnerController extends Controller
{
    /**
     * @Route("/quiz/ajax/quiz-resolu/{quiz}", name="fo_quiz_resolved", methods="POST")
     *
     * @param Request $request
     * @param ResolveQuizValidator $validator
     * @param RegisterWinnerHandler $handler
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function quizResolvedAction(
        Request $request,
        ResolveQuizValidator $validator,
        RegisterWinnerHandler $handler,
        Quiz $quiz
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            $responses = json_decode($data['responses']);

            if ($validator->validate($quiz, $responses)) {
                if ($this->getUser()) {
                    $command = new RegisterWinnerCommand($quiz, $this->getUser());
                    $handler->handle($command);
                }

                return new JsonResponse(['success' => true, 'message' => 'Félicitations ! Vous avez résolu ce quiz.']);
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Vous n\'avez pas résolu le quiz.']);
            }
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la résolution du quiz.']);
        }
    }
}
