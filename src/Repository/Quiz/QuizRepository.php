<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\UserResponse;
use App\Entity\Quiz\Winner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getMaxRank(): int
    {
        $maxRank = $this
            ->createQueryBuilder('quiz')
            ->select('MAX(quiz.rank)')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $maxRank ?? 0;
    }

    /**
     * @return Quiz[]|array<Quiz>
     */
    public function getQuizOverForUserId(int $userId): array
    {
        $queryBuilder = $this->createQueryBuilder('quiz');

        $queryBuilder
            ->join(
                Winner::class,
                'winner',
                Join::WITH,
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('winner.quiz', 'quiz'),
                    $queryBuilder->expr()->eq('winner.user', ':userId')
                )
            )
            ->setParameter('userId', $userId)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return Quiz[]|array<Quiz>
     */
    public function getQuizInProgressForUserId(int $userId): array
    {
        $queryBuilder = $this->createQueryBuilder('quiz');

        $queryBuilder
            ->join(UserResponse::class, 'userResponse', Join::WITH, $queryBuilder->expr()->eq('userResponse.user', ':userId'))
            ->join('userResponse.response', 'response', Join::WITH, $queryBuilder->expr()->eq('response.quiz', 'quiz'))
            ->setParameter('userId', $userId)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
