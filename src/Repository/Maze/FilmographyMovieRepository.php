<?php

namespace App\Repository\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\FilmographyMovie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FilmographyMovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilmographyMovie::class);
    }

    /**
     * @return FilmographyMovie[]|array<FilmographyMovie>
     */
    public function getMoviesWithActors(Actor $actor1, Actor $actor2): array
    {
        return $this->createQueryBuilder('movie')
            ->where(':actor1 MEMBER OF movie.actors')
            ->andWhere(':actor2 MEMBER OF movie.actors')
            ->setParameter('actor1', $actor1)
            ->setParameter('actor2', $actor2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTopMovieWithActors(Actor $actor1, Actor $actor2): ?FilmographyMovie
    {
        $queryBuilder = $this->createQueryBuilder('movie')
            ->where(':actor1 MEMBER OF movie.actors')
            ->andWhere(':actor2 MEMBER OF movie.actors')
            ->setParameter('actor1', $actor1)
            ->setParameter('actor2', $actor2)
            ->orderBy('movie.voteCount', 'DESC')
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function clearFilmography(): int
    {
        return $this->createQueryBuilder('movie')
            ->delete()
            ->getQuery()
            ->execute()
        ;
    }
}
