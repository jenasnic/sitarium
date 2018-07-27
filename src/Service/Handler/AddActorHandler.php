<?php

namespace App\Service\Handler;

use App\Domain\Command\AddActorCommand;
use App\Entity\Maze\Actor;
use App\Enum\Maze\FilmographyStatus;
use App\Service\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add actor from TMDB in current database
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
        if ($this->entityManager->getRepository(Actor::class)->findBy(['tmdbId' => $command->getTmdbId()])) {
            return;
        }

        $actorToAdd = $this->tmdbService->getEntity(Actor::class, $command->getTmdbId());
        $actorToAdd->setStatus(FilmographyStatus::UNINITIALIZED);

        $this->entityManager->persist($actorToAdd);
        $this->entityManager->flush();
    }
}