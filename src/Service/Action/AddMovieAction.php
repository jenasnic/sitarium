<?php

namespace App\Service\Action;

use App\Entity\Maze\Movie;
use App\Enum\Maze\CastingStatus;
use App\Service\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movie from TMDB in current database
 */
class AddMovieAction
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
     * @param int $tmdbId
     *
     * @return bool
     */
    public function execute(int $tmdbId): bool
    {
        // Check if movie already exist
        if ($this->entityManager->getRepository(Movie::class)->findBy(['tmdbId' => $tmdbId])) {
            return false;
        }

        $actorToAdd = $this->tmdbService->getEntity(Movie::class, $tmdbId);
        $actorToAdd->setStatus(CastingStatus::UNINITIALIZED);

        $this->entityManager->persist($actorToAdd);
        $this->entityManager->flush();

        return true;
    }
}
