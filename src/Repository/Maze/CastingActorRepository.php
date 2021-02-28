<?php

namespace App\Repository\Maze;

use App\Entity\Maze\CastingActor;
use App\Entity\Maze\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CastingActorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CastingActor::class);
    }

    /**
     * @return CastingActor[]|array<CastingActor>
     */
    public function getActorsWithMovies(Movie $movie1, Movie $movie2): array
    {
        return $this->createQueryBuilder('actor')
            ->where(':movie1 MEMBER OF actor.movies')
            ->andWhere(':movie2 MEMBER OF actor.movies')
            ->setParameter('movie1', $movie1)
            ->setParameter('movie2', $movie2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTopActorWithMovies(Movie $movie1, Movie $movie2): ?CastingActor
    {
        $queryBuilder = $this->createQueryBuilder('actor')
            ->where(':movie1 MEMBER OF actor.movies')
            ->andWhere(':movie2 MEMBER OF actor.movies')
            ->setParameter('movie1', $movie1)
            ->setParameter('movie2', $movie2)
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function clearCasting(): int
    {
        return $this->createQueryBuilder('actor')
            ->delete()
            ->getQuery()
            ->execute()
        ;
    }
}
