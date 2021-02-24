<?php

namespace App\Repository\Maze;

use App\Entity\Maze\Actor;
use App\Enum\Maze\FilmographyStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class ActorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actor::class);
    }

    public function getPager(?string $fullname = null, int $page = 1, int $maxPerPage = 20): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('actor');

        if (null !== $fullname) {
            $queryBuilder->andWhere(sprintf('actor.fullname like \'%%%s%%\'', $fullname));
        }

        $queryBuilder->orderBy('actor.fullname');

        $paginator = new Pagerfanta(new QueryAdapter($queryBuilder));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @return Actor[]|array<Actor>
     */
    public function getActorsWithoutFilmography(): array
    {
        return $this->createQueryBuilder('actor')
            ->where('SIZE(actor.movies) = 0')
            ->getQuery()
            ->getResult()
        ;
    }

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
     * NOTE : unable to build same entity => use TMDB identifiers instead...
     *
     * @param array<int>|null $actorIds list of TMDB identifiers for actors we want to get links (null to get links for all existing actors)
     * @param int $minVoteCount Minimum vote count value for movies used to find link between actors. This allows to use only famous movie when linking actors.
     *                          Default value 0 means that we ignore vote count (i.e. use all movies to link actors...)
     *
     * @return array<array<string, int>> array of linked actors using TMDB identifiers in a map with both keys : main_actor_identifier and linked_actor_identifier
     */
    public function getLinkedActorsIds(array $actorIds = null, $minVoteCount = 0): array
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
