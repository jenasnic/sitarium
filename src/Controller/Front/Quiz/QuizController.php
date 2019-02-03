<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\ClearUserResponseCommand;
use App\Entity\Quiz\Quiz;
use App\Repository\Quiz\QuizRepository;
use App\Repository\Quiz\UserResponseRepository;
use App\Service\Handler\Quiz\ClearUserResponseHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    /**
     * @Route("/quiz-en-images", name="fo_quiz")
     *
     * @param QuizRepository $quizRepository
     *
     * @return Response
     */
    public function indexAction(QuizRepository $quizRepository): Response
    {
        return $this->render('front/quiz/index.html.twig', [
            'quizs' => $quizRepository->findBy(['published' => true], ['rank' => 'asc']),
        ]);
    }

    /**
     * @Route("/quiz/jouer/{slug}", name="fo_quiz_play")
     *
     * @param UserResponseRepository $userResponseRepository
     * @param Quiz $quiz
     *
     * @return Response
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
     * @Route("/quiz/rejouer/{quiz}", requirements={"quiz" = "\d+"}, name="fo_quiz_replay")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param ClearUserResponseHandler $handler
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function replayAction(ClearUserResponseHandler $handler, Quiz $quiz): Response
    {
        try {
            $handler->handle(new ClearUserResponseCommand($this->getUser(), $quiz));
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la remise à zéro de vos réponses.');
        }

        return $this->redirectToRoute('fo_quiz_play', ['slug' => $quiz->getSlug()]);
    }
}
