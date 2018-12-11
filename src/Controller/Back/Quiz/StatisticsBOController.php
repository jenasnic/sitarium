<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response as QuizResponse;
use App\Entity\User;
use App\Repository\Quiz\UserResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsBOController extends Controller
{
    /**
     * @var UserResponseRepository
     */
    protected $userResponseReporitory;

    /**
     * @param UserResponseRepository $userResponseReporitory
     */
    public function __construct(UserResponseRepository $userResponseReporitory)
    {
        $this->userResponseReporitory = $userResponseReporitory;
    }

    /**
     * @Route("/admin/quiz/statistics", name="bo_quiz_statistics")
     *
     * @return Response
     */
    public function statisticsAction(): Response
    {
        return $this->render('back/quiz/statistics/index.html.twig', [
            'quizInfos' => $this->userResponseReporitory->getGlobalStatisticsForQuiz(),
            'userInfos' => $this->userResponseReporitory->getGlobalStatisticsForUsers(),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/quiz/{quiz}", requirements={"quiz" = "\d+"}, name="bo_quiz_statistics_quiz")
     *
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function statisticsQuizAction(Quiz $quiz): Response
    {
        return $this->render('back/quiz/statistics/quiz.html.twig', [
            'quiz' => $quiz,
            'responseInfos' => $this->userResponseReporitory->getResponsesStatisticsForQuizId($quiz->getId()),
            'userInfos' => $this->userResponseReporitory->getUsersStatisticsForQuizId($quiz->getId()),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/user/{user}", requirements={"user" = "\d+"}, name="bo_quiz_statistics_user")
     *
     * @param User $user
     *
     * @return Response
     */
    public function statisticsUserAction(User $user): Response
    {
        return $this->render('back/quiz/statistics/user.html.twig', [
            'user' => $user,
            'quizInfos' => $this->userResponseReporitory->getQuizStatisticsForUserId($user->getId()),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/quiz/response/{response}", requirements={"response" = "\d+"}, name="bo_quiz_statistics_response")
     *
     * @param QuizResponse $response
     *
     * @return Response
     */
    public function statisticsQuizResponseAction(QuizResponse $response): Response
    {
        return $this->render('back/quiz/statistics/response.html.twig', [
            'responses' => $this->userResponseReporitory->getUserResponsesForResponseId($response->getId()),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/user/response/{user}/{quiz}", requirements={"user" = "\d+", "quiz" = "\d+"}, name="bo_quiz_statistics_user_response")
     *
     * @param User $user
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function statisticsUserResponseAction(User $user, Quiz $quiz): Response
    {
        return $this->render('back/quiz/statistics/user_response.html.twig', [
            'responses' => $this->userResponseReporitory->getResponsesForUserIdAndQuizId($user->getId(), $quiz->getId()),
        ]);
    }
}
