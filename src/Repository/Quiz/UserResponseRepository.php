<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\UserResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * QuizUserResponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserResponseRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserResponse::class);
    }

    /**
     * Allows to get all responses found by specified user ID for specified quiz ID.
     *
     * @param int $userId Identifier of user we want to get responses already found.
     * @param int $quizId Identifier of quiz we want to get responses already found by previous user.
     *
     * @return array List of responses found by specified user for specified quiz.
     */
    public function getResponsesForUserIdAndQuizId(int $userId, int $quizId): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->join('userResponse.user', 'user')
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->where('user.id = :userId')
            ->andWhere('quiz.id = :quizId')
            ->orderBy('userResponse.date', 'DESC')
            ->setParameters([
                'userId' => $userId,
                'quizId' => $quizId,
            ])
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int $quizId
     */
    public function removeResponsesForQuizId(int $quizId)
    {
        $responses = $this
            ->createQueryBuilder('userResponse')
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->where('quiz.id = :quizId')
            ->setParameter('quizId', $quizId)
            ->getQuery()
            ->getResult()
        ;

        foreach ($responses as $response) {
            $this->_em->remove($response);
        }

        $this->_em->flush();
    }

    /**
     * @param int $userId
     */
    public function removeResponsesForUserId(int $userId)
    {
        $responses = $this
            ->createQueryBuilder('userResponse')
            ->join('userResponse.user', 'user')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;

        foreach ($responses as $response) {
            $this->_em->remove($response);
        }

        $this->_em->flush();
    }

    /**
     * Allows to get statistics about quiz giving number of user having played to quiz.
     * Data are returned as associative array with following key/value :
     *      - id : quiz identifier (as int)
     *      - name : name of quiz (as string)
     *      - userCount : number of player for current quiz (as int)
     *
     * @return array Array of informations about quiz with keys : id, name and userCount.
     */
    public function getGlobalStatisticsForQuiz(): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->select('quiz.id as id, quiz.name as name, COUNT(distinct user) as userCount')
            ->join('userResponse.user', 'user')
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->groupBy('quiz.id')
            ->orderBy('userCount', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to get statistics about player giving number of responses they have found for quiz.
     * Data are returned as associative array with following key/data :
     *      - id : user identifier (as int)
     *      - firstname : firstname of current user (as string)
     *      - lastname : lastname of current user (as string)
     *      - responseCount : number of response found for current user (as int)
     *      - lastDate : last date user has played (as DateTime)
     *
     * @return array Array of informations about player with keys : id, firstname, lastname, responseCount and lastDate.
     */
    public function getGlobalStatisticsForUsers(): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->select(
                'user.id as id',
                'user.firstname as firstname',
                'user.lastname as lastname',
                'COUNT(response) as responseCount',
                'MAX(userResponse.date) as lastDate'
            )
            ->join('userResponse.user', 'user')
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->groupBy('user.id')
            ->orderBy('responseCount', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to get statistics about response for specified quiz giving number of player having found response...
     * Data are returned as associative array with following key/data :
     *      - id : response identifier (as int)
     *      - title : value of response (as string)
     *      - foundCount : number of times response has been found (as int)
     *      - lastDate : last date the response has been found (as DateTime)
     *
     * @param int $quizId
     *
     * @return array Array of informations about player with keys : id, title, foundCount and lastDate.
     */
    public function getResponsesStatisticsForQuizId(int $quizId): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->select(
                'response.id as id',
                'response.title as title',
                'COUNT(response) as foundCount',
                'MAX(userResponse.date) as lastDate'
            )
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->groupBy('response.id')
            ->where('quiz.id = :quizId')
            ->setParameter('quizId', $quizId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to get statistics about players for specified quiz giving number of response found.
     * Data are returned as associative array with following key/data :
     *      - id : user identifier (as int)
     *      - firstname : firstname of current user (as string)
     *      - lastname : lastname of current user (as string)
     *      - responseCount : number of response found for current user (as int)
     *      - lastDate : last date user has played (as DateTime)
     *
     * @param int $quizId
     *
     * @return array Array of informations about player with keys : id, firstname, lastname, responseCount and lastDate.
     */
    public function getUsersStatisticsForQuizId(int $quizId): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->select(
                'user.id as id',
                'user.firstname as firstname',
                'user.lastname as lastname',
                'COUNT(user) as responseCount',
                'MAX(userResponse.date) as lastDate'
            )
            ->join('userResponse.user', 'user')
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->groupBy('user.id')
            ->where('quiz.id = :quizId')
            ->setParameter('quizId', $quizId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to get statistics about resolved quiz for specified player.
     * Data are returned as associative array with following key/data :
     *      - id : quiz identifier (as int)
     *      - name : name of quiz (as string)
     *      - responseCount : number of response found for current quiz (as int)
     *      - lastDate : last date user has played for current quiz (as DateTime)
     *
     * @param int $userId
     *
     * @return array Array of informations about quiz with keys : id, name, responseCount and lastDate.
     */
    public function getQuizStatisticsForUserId(int $userId): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->select(
                'quiz.id as id',
                'quiz.name as name',
                'COUNT(quiz) as responseCount',
                'MAX(userResponse.date) as lastDate'
            )
            ->join('userResponse.user', 'user')
            ->join('userResponse.response', 'response')
            ->join('response.quiz', 'quiz')
            ->groupBy('quiz.id')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to get all occurences of UserResponse for specified response ID (=> give all users having found response).
     *
     * @param int $responseId
     *
     * @return array List of user responses matching specified parameter.
     */
    public function getUserResponsesForResponseId(int $responseId): array
    {
        return $this
            ->createQueryBuilder('userResponse')
            ->join('userResponse.response', 'response')
            ->where('response.id = :responseId')
            ->setParameter('responseId', $responseId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to check if specified response identifier already exist for specified user (i.e. user has already found response).
     *
     * @param int $userId
     * @param int $responseId
     *
     * @return bool TRUE if specified response has already been found by specified user, FALSE either.
     */
    public function checkExistingResponseForUserId(int $userId, int $responseId): bool
    {
        $queryBuilder = $this
            ->createQueryBuilder('userResponse')
            ->join('userResponse.user', 'user')
            ->join('userResponse.response', 'response')
            ->where('user.id = :userId')
            ->andWhere('response.id = :responseId')
            ->orderBy('userResponse.date', 'DESC')
            ->setParameter('userId', $userId)
            ->setParameter('responseId', $responseId)
        ;

        return count($queryBuilder->getQuery()->getResult()) > 0;
    }
}
