<?php

namespace App\Repository\Maze;

use App\Enum\Maze\FilmographyStatusEnum;
use App\Entity\Maze\Actor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ActorRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActorRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Actor::class);
    }

    /**
     * @param string $fullname
     * @param int $page
     * @param int $maxPerPage
     *
     * @return Pagerfanta
     */
    public function getPager(?string $fullname = null, int $page = 1, int $maxPerPage = 20): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('actor');

        if (null !== $fullname) {
            $queryBuilder->andWhere(sprintf('actor.fullname like \'%%%s%%\'', $fullname));
        }

        $queryBuilder->orderBy('actor.fullname');

        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Allows to get actors that doesn't appear in a movie.
     *
     * @return array array of actors without filmography (as Actor)
     */
    public function getActorsWithoutFilmography(): array
    {
        return $this->createQueryBuilder('actor')
            ->where('SIZE(actor.movies) = 0')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Allows to reset actors' status to default (i.e. filmography_to_check).
     *
     * @return int updated rows count
     */
    public function resetActorStatus(): int
    {
        $queryBuilder = $this->createQueryBuilder('actor');

        return $queryBuilder->update()
        ->set('actor.status', $queryBuilder->expr()->literal(FilmographyStatusEnum::UNINITIALIZED))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Allows to find linked actors.
     *
     * @param array (optionnal) $actorIds (default null) List of TMDB identifiers (as integer) for actors we want to get links.
     * Default value null means that we get links for all existing actors.
     * @param int (optionnal) $minVoteCount (default = 0) Minimum vote count value for movies used to find link between actors. This allows to use only famous movie when linking actors...
     * Default value 0 means that we ignore vote count (i.e. use all movies to link actors...)
     *
     * @return array Array of linked actors using TMDB identifiers in a map with both keys : main_actor_identifier and linked_actor_identifier.
     * NOTE : unable to build same entity => use TMDB identifiers instead...
     */
    public function getLinkedActorsIds($actorIds = null, $minVoteCount = 0): array
    {
        $queryBuilder = $this->createQueryBuilder('main_actor');

        $queryBuilder
            ->select('main_actor.tmdbId as main_actor_identifier, linked_actor.tmdbId as linked_actor_identifier')
            ->distinct()
        ;

        // Make a join with movies (for main actors)
        if ($minVoteCount > 0) {
            $queryBuilder->join('main_actor.movies', 'common_movies', Join::WITH, $queryBuilder->expr()->gte('common_movies.voteCount', $minVoteCount));
        } else {
            $queryBuilder->join('main_actor.movies', 'common_movies');
        }

        $queryBuilder->join('common_movies.actors', 'linked_actor', Join::WITH, $queryBuilder->expr()->neq('linked_actor.tmdbId', 'main_actor.tmdbId'));

        // Add condition on identifier for actors
        if (null != $actorIds) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('main_actor.tmdbId', $actorIds))
                ->andWhere($queryBuilder->expr()->in('linked_actor.tmdbId', $actorIds))
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
