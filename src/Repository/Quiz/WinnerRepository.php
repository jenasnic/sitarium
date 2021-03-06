<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\Winner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class WinnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Winner::class);
    }

    public function getPagerForQuizId(int $quizId, ?string $name = null, int $page = 1, int $maxPerPage = 20): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('winner');

        $queryBuilder
            ->join('winner.quiz', 'quiz')
            ->andWhere('quiz.id = :quizId')
            ->setParameter('quizId', $quizId)
            ->orderBy('winner.date')
        ;

        if (null !== $name) {
            $queryBuilder
                ->join('winner.user', 'user')
                ->andWhere($queryBuilder->expr()->orX(
                    sprintf('user.firstname like \'%%%s%%\'', $name),
                    sprintf('user.lastname like \'%%%s%%\'', $name)
                ))
            ;
        }

        $paginator = new Pagerfanta(new QueryAdapter($queryBuilder));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @return Winner[]|array<Winner>
     */
    public function getWinnersForQuizId(int $quizId): array
    {
        return $this
            ->createQueryBuilder('winner')
            ->join('winner.quiz', 'quiz')
            ->where('quiz.id = :quizId')
            ->setParameter('quizId', $quizId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function removeWinnersForQuizId(int $quizId): void
    {
        $winners = $this->getWinnersForQuizId($quizId);

        foreach ($winners as $winner) {
            $this->getEntityManager()->remove($winner);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @return Winner[]|array<Winner>
     */
    public function getWinnersForUserId(int $userId): array
    {
        return $this
            ->createQueryBuilder('winner')
            ->join('winner.user', 'user')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function removeWinnersForUserId(int $userId): void
    {
        $winners = $this->getWinnersForUserId($userId);

        foreach ($winners as $winner) {
            $this->getEntityManager()->remove($winner);
        }

        $this->getEntityManager()->flush();
    }
}
