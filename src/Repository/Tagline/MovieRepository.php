<?php

namespace App\Repository\Tagline;

use App\Entity\Tagline\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function getPager(?string $title = null, int $page = 1, int $maxPerPage = 20): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('movie');

        if (null !== $title) {
            $queryBuilder->andWhere(sprintf('movie.title like \'%%%s%%\'', $title));
        }

        $queryBuilder->orderBy('movie.title');

        $paginator = new Pagerfanta(new QueryAdapter($queryBuilder));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @return Movie[]|array<Movie>
     */
    public function findByGenre(int $tmdbId): array
    {
        $queryBuilder = $this->createQueryBuilder('movie');

        $queryBuilder
            ->innerJoin('movie.genres', 'genre')
            ->where('genre.tmdbId = :genreId')
            ->setParameter('genreId', $tmdbId)
            ->orderBy('movie.title')
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
