<?php

namespace App\Repository\Tagline;

use App\Entity\Tagline\Genre;
use App\Entity\Tagline\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * GenreRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenreRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    /**
     * Allows to get infos for genre with id, name and number of movies for each genre.
     * NOTE : Return only used genres.
     *
     * @return array
     */
    public function getInfos(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder
            ->from(Movie::class, 'movie')
            ->innerJoin('movie.genres', 'genre')
            ->select('genre.tmdbId', 'genre.name', 'COUNT(genre.tmdbId) as movieCount')
            ->groupBy('genre.tmdbId')
            ->orderBy('genre.name')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Allows to get unused genres.
     *
     * @return Genre[]|array
     */
    public function findUnusedGenres(): array
    {
        $subQuery = $this->getEntityManager()->createQueryBuilder();
        $subQuery
            ->from(Movie::class, 'movie')
            ->join('movie.genres', 'movie_genre')
            ->select('movie_genre.tmdbId')
            ->distinct()
        ;

        $queryBuilder = $this->createQueryBuilder('genre');
        $queryBuilder
            ->where($queryBuilder->expr()->notIn('genre.tmdbId',  $subQuery->getDQL()))
            ->orderBy('genre.name')
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
