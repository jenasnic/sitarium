<?php

namespace App\Service\Action;

use App\Entity\Maze\CastingActor;
use App\Repository\Maze\MovieRepository;
use App\Repository\Maze\CastingActorRepository;
use App\Tool\TextUtil;

/**
 * This class allows to check if given response to link both movies (in movie path) is valide or not.
 * => Check if given actor name match with one of common actors between both movies.
 */
class CheckMoviePathResponseAction
{
    /**
     * @var MovieRepository
     */
    protected $movieRepository;

    /**
     * @var CastingActorRepository
     */
    protected $actorRepository;

    /**
     * @param MovieRepository $movieRepository
     * @param CastingActorRepository $actorRepository
     */
    public function __construct(MovieRepository $movieRepository, CastingActorRepository $actorRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->actorRepository = $actorRepository;
    }

    /**
     * Allows to check if both specified movies define common actor matching specified name.
     *
     * @param int $actorId1
     * @param int $actorId2
     * @param string $movieTitle
     *
     * @return CastingActor|null Common actor matching specified name or null if no common actor found.
     */
    public function execute(int $movieId1, int $movieId2, string $actorName): ?CastingActor
    {
        $movie1 = $this->movieRepository->find($movieId1);
        $movie2 = $this->movieRepository->find($movieId2);

        $commonActors = array_uintersect(
            $movie1->getActors()->toArray(),
            $movie2->getActors()->toArray(),
            function (CastingActor $actor1, CastingActor $actor2) {
                return $actor1->getTmdbId() - $actor2->getTmdbId();
            }
        );

        // Check if specified actor name match with one of common actors
        $actorNameToCompare = TextUtil::sanitize($actorName);
        foreach ($commonActors as $actor) {
            if ($actorNameToCompare === TextUtil::sanitize($actor->getFullname())) {
                return $actor;
            }
        }

        return null;
    }
}
