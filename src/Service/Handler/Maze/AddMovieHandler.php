<?php

namespace App\Service\Handler\Maze;

use App\Domain\Command\Maze\AddMovieCommand;
use App\Entity\Maze\Movie;
use App\Enum\Maze\CastingStatus;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movie from TMDB in current database.
 */
class AddMovieHandler
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
     * @param AddMovieCommand $command
     */
    public function handle(AddMovieCommand $command)
    {
        // Check if movie already exist
        if (null !== $this->entityManager->getRepository(Movie::class)->find($command->getTmdbId())) {
            return;
        }

        $actorToAdd = $this->tmdbService->getEntity(Movie::class, $command->getTmdbId());
        $actorToAdd->setStatus(CastingStatus::UNINITIALIZED);

        $this->entityManager->persist($actorToAdd);
        $this->entityManager->flush();
    }
}
