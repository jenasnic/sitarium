<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\ClearUserResponseCommand;
use App\Entity\Quiz\Quiz;
use App\Repository\Quiz\QuizRepository;
use App\Repository\Quiz\UserResponseRepository;
use App\Service\Handler\Quiz\ClearUserResponseHandler;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz-en-images", name="fo_quiz")
     */
    public function indexAction(QuizRepository $quizRepository): Response
    {
        return $this->render('front/quiz/index.html.twig', [
            'quizs' => $quizRepository->findBy(['published' => true], ['rank' => 'asc']),
        ]);
    }

    /**
     * @Route("/quiz/jouer/{slug}", name="fo_quiz_play")
     */
    public function playAction(UserResponseRepository $userResponseRepository, Quiz $quiz): Response
    {
        $loggedUser = $this->getUser();

        // If user is logged => get associated responses for specified quiz
        $userResponses = $loggedUser ? $userResponseRepository->getResponsesForUserIdAndQuizId($loggedUser->getId(), $quiz->getId()) : [];

        return $this->render('front/quiz/play.html.twig', [
            'quiz' => $quiz,
            'userResponses' => $userResponses,
        ]);
    }

    /**
     * @Route("/quiz/rejouer/{quiz}", requirements={"quiz": "\d+"}, name="fo_quiz_replay")
     * @Security("is_granted('ROLE_USER')")
     */
    public function replayAction(TranslatorInterface $translator, ClearUserResponseHandler $handler, Quiz $quiz): Response
    {
        try {
            $handler->handle(new ClearUserResponseCommand($this->getUser(), $quiz));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('front.quiz.response.reset_error'));
        }

        return $this->redirectToRoute('fo_quiz_play', ['slug' => $quiz->getSlug()]);
    }
}
