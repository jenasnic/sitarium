<?php

namespace App\Service\Handler\Maze;

use App\Domain\Command\Maze\AddActorCommand;
use App\Entity\Maze\Actor;
use App\Enum\Maze\FilmographyStatus;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add actor from TMDB in current database.
 */
class AddActorHandler
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbService;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TmdbApiService $tmdbService, EntityManagerInterface $entityManager)
    {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddActorCommand $command
     */
    public function handle(AddActorCommand $command)
    {
        // Check if actor already exist
        if (null !== $this->entityManager->getRepository(Actor::class)->find($command->getTmdbId())) {
            return;
        }

        $actorToAdd = $this->tmdbService->getEntity(Actor::class, $command->getTmdbId());
        $actorToAdd->setStatus(FilmographyStatus::UNINITIALIZED);

        $this->entityManager->persist($actorToAdd);
        $this->entityManager->flush();
    }
}
