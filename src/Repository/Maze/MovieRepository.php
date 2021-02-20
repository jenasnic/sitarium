<?php

namespace App\Repository\Maze;

use App\Enum\Maze\CastingStatusEnum;
use App\Entity\Maze\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * MovieRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @param string $title
     * @param int $page
     * @param int $maxPerPage
     *
     * @return Pagerfanta
     */
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
     * Allows to get movies that doesn't have any actor.
     *
     * @return array array of movies without casting (as Movie)
     */
    public function getMoviesWithoutCasting(): array
    {
        return $this->createQueryBuilder('movie')
            ->where('SIZE(movie.actors) = 0')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to reset movies' status to default (i.e. casting_to_check).
     *
     * @return int updated rows count
     */
    public function resetMoviesStatus(): int
    {
        $queryBuilder = $this->createQueryBuilder('movie');

        $queryBuilder
            ->update()
            ->set('movie.status', $queryBuilder->expr()->literal(CastingStatusEnum::UNINITIALIZED))
        ;

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * Allows to find linked movies.
     *
     * @param array (optionnal) $movieIds (default null) List of TMDB identifiers (as integer) for movies we want to get links.
     * Default value null means that we get links for all existing movies.
     *
     * @return array Array of linked movies using TMDB identifiers in a map with both keys : main_movie_identifier and linked_movie_identifier.
     * NOTE : unable to build same entity => use TMDB identifiers instead...
     */
    public function getLinkedMoviesIds($movieIds = null): array
    {
        $queryBuilder = $this->createQueryBuilder('main_movie');

        $queryBuilder
            ->select('main_movie.tmdbId as main_movie_identifier, linked_movie.tmdbId as linked_movie_identifier')
            ->distinct()
            ->join('main_movie.actors', 'common_actors')
            ->join('common_actors.movies', 'linked_movie', Join::WITH, $queryBuilder->expr()->neq('linked_movie.tmdbId', 'main_movie.tmdbId'))
        ;

        // Add condition on identifier for movies
        if (null != $movieIds) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('main_movie.tmdbId', $movieIds))
                ->andWhere($queryBuilder->expr()->in('linked_movie.tmdbId', $movieIds))
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
