<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\RegisterWinnerCommand;
use App\Entity\Quiz\Quiz;
use App\Service\Handler\Quiz\RegisterWinnerHandler;
use App\Service\Quiz\ResolveQuizValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class WinnerController extends AbstractController
{
    /**
     * @Route("/quiz/ajax/quiz-resolu/{quiz}", name="fo_quiz_resolved", methods="POST")
     */
    public function quizResolvedAction(
        Request $request,
        TranslatorInterface $translator,
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

                    $message = $translator->trans('front.quiz.winner.quiz_resolved.logged_user', ['%username%' => $this->getUser()->getDisplayName()]);
                } else {
                    $message = $translator->trans('front.quiz.winner.quiz_resolved');
                }

                return new JsonResponse(['success' => true, 'message' => $message]);
            }

            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.quiz.winner.quiz_not_resolved')]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.quiz.winner.quiz_resolve_error')]);
        }
    }
}
