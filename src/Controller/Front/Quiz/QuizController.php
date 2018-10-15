<?php

namespace App\Controller\Front\Quiz;

use App\Entity\Quiz\Quiz;
use App\Repository\Quiz\QuizRepository;
use App\Repository\Quiz\UserResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    /**
     * @Route("/quiz", name="fo_quiz")
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
     * @Route("/quiz/jouer/{quiz}", requirements={"quiz" = "\d+"}, name="fo_quiz_play")
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
            'userResponses' => $userResponses
        ]);
    }
}
