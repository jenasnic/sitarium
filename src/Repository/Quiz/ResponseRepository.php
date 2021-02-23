<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

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
     * @return Response[]|array<Response>
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
