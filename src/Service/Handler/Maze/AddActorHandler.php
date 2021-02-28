<?php

namespace App\Service\Handler\Maze;

use App\Domain\Command\Maze\AddActorCommand;
use App\Repository\Maze\ActorRepository;
use App\Service\Converter\ActorConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add actor from TMDB in current database.
 */
class AddActorHandler
{
    protected TmdbDataProvider $tmdbDataProvider;

    protected ActorConverter $actorConverter;

    protected ActorRepository $actorRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        ActorConverter $actorConverter,
        ActorRepository $actorRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->actorConverter = $actorConverter;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(AddActorCommand $command): void
    {
        // Check if actor already exist
        if (null !== $this->actorRepository->find($command->getTmdbId())) {
            return;
        }

        $tmdbActor = $this->tmdbDataProvider->getActor($command->getTmdbId());
        $actorToAdd = $this->actorConverter->convert($tmdbActor);

        $this->entityManager->persist($actorToAdd);
        $this->entityManager->flush();
    }
}
