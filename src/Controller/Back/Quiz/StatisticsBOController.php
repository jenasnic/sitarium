<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response;
use App\Entity\User\User;
use App\Repository\Quiz\UserResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    function __construct(UserResponseRepository $userResponseReporitory)
    {
        $this->userResponseReporitory = $userResponseReporitory;
    }

    /**
     * @Route("/admin/quiz/statistics", name="bo_quiz_statistics")
     */
    public function statisticsAction()
    {
        return $this->render('back/quiz/statistics/index.html.twig', [
            'quizInfos' => $this->userResponseReporitory->getGlobalStatisticsForQuiz(),
            'userInfos' => $this->userResponseReporitory->getGlobalStatisticsForUsers(),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/quiz/{quiz}", requirements={"quiz" = "\d+"}, name="bo_quiz_statistics_quiz")
     */
    public function statisticsQuizAction(Quiz $quiz)
    {
        return $this->render('back/quiz/statistics/quiz.html.twig', [
            'quiz' => $quiz,
            'responseInfos' => $this->userResponseReporitory->getResponsesStatisticsForQuizId($quiz->getId()),
            'userInfos' => $this->userResponseReporitory->getUsersStatisticsForQuizId($quiz->getId())
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/user/{user}", requirements={"user" = "\d+"}, name="bo_quiz_statistics_user")
     */
    public function statisticsUserAction(User $user)
    {
        return $this->render('back/quiz/statistics/user.html.twig', [
            'user' => $user,
            'quizInfos' => $this->userResponseReporitory->getQuizStatisticsForUserId($user->getId()),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/quiz/response/{response}", requirements={"response" = "\d+"}, name="bo_quiz_statistics_response")
     */
    public function statisticsQuizResponseAction(Response $response)
    {
        return $this->render('back/quiz/statistics/response.html.twig', [
            'responses' => $this->userResponseReporitory->getUserResponsesForResponseId($response->getId()),
        ]);
    }

    /**
     * @Route("/admin/quiz/statistics/user/response/{user}/{quiz}", requirements={"user" = "\d+", "quiz" = "\d+"}, name="bo_quiz_statistics_user_response")
     */
    public function statisticsUserResponseAction(User $user, Quiz $quiz)
    {
        return $this->render('back/quiz/statistics/user_response.html.twig', [
            'responses' => $this->userResponseReporitory->getResponsesForUserIdAndQuizId($user->getId(), $quiz->getId()),
        ]);
    }
}
