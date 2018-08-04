<?php

namespace App\Repository\Quiz;

use App\Entity\Quiz\Winner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * WinnerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WinnerRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Winner::class);
    }

    /**
     * @param int $quizId
     *
     * @return array
     */
    public function findWinnersForQuizId(int $quizId): array
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

    /**
     * @param int $quizId
     */
    public function removeWinnersForQuizId(int $quizId)
    {
        $winners = $this->getWinnerForQuizId($quizId);

        foreach ($winners as $winner) {
            $this->_em->remove($winner);
        }

        $this->_em->flush();
    }
}