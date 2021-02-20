<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * QuizResponseRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResponseRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

    /**
     * Allows to check if response match for quiz.
     *
     * @param string $response response we check if exist for quiz
     * @param int $quizId quiz we are searching for responses
     *
     * @return Response|null response matching specified parameters
     */
    public function searchMatchingResponseForQuizId(string $response, int $quizId): ?Response
    {
        return $this
            ->createQueryBuilder('response')
            ->join('response.quiz', 'quiz')
            ->where('quiz.id = :quizId')
            ->andWhere('response.responses LIKE :response')
            ->setParameters([
                'quizId' => $quizId,
                'response' => sprintf('%%;%s;%%', $response),
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Allows to search response matching specified coordinates.
     *
     * @param int $positionX coordonate X we are searching for responses
     * @param int $positionY coordonate Y we are searching for responses
     * @param int $quizId quiz we are searching for responses
     *
     * @return Response[]|array Array of responses matching specified paramters (as Response)
     */
    public function getResponsesWithCoordonates(int $positionX, int $positionY, int $quizId): array
    {
        $queryBuilder = $this
            ->createQueryBuilder('response')
            ->join('response.quiz', 'quiz')
            ->where('quiz.id = :quizId')
            ->andWhere(':positionX >= response.positionX')
            ->andWhere(':positionX <= (response.positionX + response.width)')
            ->andWhere(':positionY >= response.positionY')
            ->andWhere(':positionY <= (response.positionY + response.height)')
            ->setParameters([
                'quizId' => $quizId,
                'positionX' => $positionX,
                'positionY' => $positionY,
            ])
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
