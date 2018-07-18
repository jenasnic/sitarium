<?php

namespace App\Repository\Maze;

use App\Entity\Maze\CastingActor;
use App\Entity\Maze\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CastingActorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CastingActorRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CastingActor::class);
    }

    /**
     * Allows to get actors with both specified movies.
     *
     * @param Movie First actor that must appear in searched movies.
     * @param Movie Second actor that must appear in searched movies.
     *
     * @return array Array of movies with specified actors (as Movie).
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
}
