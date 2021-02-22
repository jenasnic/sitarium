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
    /**
     * @var TmdbDataProvider
     */
    protected $tmdbDataProvider;

    /**
     * @var ActorConverter
     */
    protected $actorConverter;

    /**
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param ActorConverter $actorConverter
     * @param ActorRepository $actorRepository
     * @param EntityManagerInterface $entityManager
     */
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

    /**
     * @param AddActorCommand $command
     */
    public function handle(AddActorCommand $command)
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
