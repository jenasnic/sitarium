<?php

namespace App\Service;

use App\Entity\Maze\Actor;
use App\Model\Maze\ActorGraphItem;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class allows to build actors graph (i.e. list of actors with all links between them through common movies).
 */
class ActorGraphBuilder
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Allows to build a graph with actors and links between them.
     * This graph is returned as a map with an entry for each point of the graph (i.e. each actor).
     * => Entry point of the graph (representing an actor) can be reach using TMDB identifier of actor.
     *
     * @param array $actorIds Array of TMDB identifiers (as integer) for actors to use to build graph.
     * Default value null means that we build full graph for all existing actors.
     * @param int $minVoteCount (default = 0) Minimum vote count value for movies used to build actor graph. This allows to use only famous movie when building graph using movies as link between actors.
     * Default value 0 means that we ignore vote count (i.e. use all movies to link actors...)
     *
     * @return array Map of ActorGraphItem with TMDB identifier as key and ActorGraphItem as value.
     */
    public function buildGraph($actorIds = null, $minVoteCount = 0): array
    {
        $actorMap = [];
        $actorGraphItemMap = [];

        $actorList = (null === $actorIds)
            ? $this->entityManager->getRepository(Actor::class)->findAll()
            : $this->entityManager->getRepository(Actor::class)->findBy(['tmdbId' => $actorIds])
        ;

        // First step : Build actors map with tmdbId as key and matching actor as value
        foreach ($actorList as $actor) {
            $actorMap[$actor->getTmdbId()] = $actor;
        }

        // Second step : get all linked actors array
        $linkedActorList = $this->entityManager->getRepository(Actor::class)->getLinkedActorsId($actorIds, $minVoteCount);

        // Third step : build graph with actors
        foreach ($linkedActorList as $linkedActorIds) {
            $mainActorId = $linkedActorIds['main_actor_identifier'];
            $linkedActorId = $linkedActorIds['linked_actor_identifier'];

            // Check if actors are already set in actorGraphItemMap
            if (!isset($actorGraphItemMap[$mainActorId])) {
                $actorGraphItemMap[$mainActorId] = new ActorGraphItem($actorMap[$mainActorId]);
            }
            if (!isset($actorGraphItemMap[$linkedActorId])) {
                $actorGraphItemMap[$linkedActorId] = new ActorGraphItem($actorMap[$linkedActorId]);
            }

            $mainActorGraphItem = $actorGraphItemMap[$mainActorId];
            $linkedActorGraphItem = $actorGraphItemMap[$linkedActorId];

            // Update main actor (as ActorGraphItem) if needed (add linked actor if not already added)
            if (!in_array($linkedActorGraphItem, $mainActorGraphItem->getLinkedActors())) {
                $mainActorGraphItem->addLinkedActor($linkedActorGraphItem);
            }
        }

        return $actorGraphItemMap;
    }
}
