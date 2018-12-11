<?php

namespace App\Service\Maze;

use App\Repository\Maze\ActorRepository;
use App\Model\Maze\MazeGraphItem;

/**
 * This class allows to build actors graph (i.e. list of actors with all links between them through common movies).
 */
class ActorGraphBuilder
{
    /**
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @param ActorRepository $actorRepository
     */
    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
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
     * @return array map of MazeGraphItem with TMDB identifier as key and MazeGraphItem as value
     */
    public function buildGraph($actorIds = null, $minVoteCount = 0): array
    {
        $actorMap = [];
        $mazeGraphItemMap = [];

        $actorList = (null === $actorIds)
            ? $this->actorRepository->findAll()
            : $this->actorRepository->findBy(['tmdbId' => $actorIds])
        ;

        // First step : Build actors map with tmdbId as key and matching actor as value
        foreach ($actorList as $actor) {
            $actorMap[$actor->getTmdbId()] = $actor;
        }

        // Second step : get all linked actors array
        $linkedActorsIds = $this->actorRepository->getLinkedActorsIds($actorIds, $minVoteCount);

        // Third step : build graph with actors
        foreach ($linkedActorsIds as $actorsIds) {
            $mainActorId = $actorsIds['main_actor_identifier'];
            $linkedActorId = $actorsIds['linked_actor_identifier'];

            // Check if actors are already set in mazeGraphItemMap
            if (!isset($mazeGraphItemMap[$mainActorId])) {
                $mazeGraphItemMap[$mainActorId] = new MazeGraphItem($actorMap[$mainActorId]);
            }
            if (!isset($mazeGraphItemMap[$linkedActorId])) {
                $mazeGraphItemMap[$linkedActorId] = new MazeGraphItem($actorMap[$linkedActorId]);
            }

            $mainActorGraphItem = $mazeGraphItemMap[$mainActorId];
            $linkedActorGraphItem = $mazeGraphItemMap[$linkedActorId];

            // Update main actor (as ActorGraphItem) if needed (add linked actor if not already added)
            if (!in_array($linkedActorGraphItem, $mainActorGraphItem->getLinkedItems())) {
                $mainActorGraphItem->addLinkedItem($linkedActorGraphItem);
            }
        }

        return $mazeGraphItemMap;
    }
}
