<?php

namespace App\Service\Action;

use App\Entity\Maze\Actor;
use App\Enum\Maze\FilmographyStatus;
use App\Service\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add actor from TMDB in current database
 */
class AddActorAction
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
        // Check if actor already exist
        if ($this->entityManager->getRepository(Actor::class)->findBy(['tmdbId' => $tmdbId])) {
            return false;
        }

        $actorToAdd = $this->tmdbService->getEntity(Actor::class, $tmdbId);
        $actorToAdd->setStatus(FilmographyStatus::UNINITIALIZED);

        $this->entityManager->persist($actorToAdd);
        $this->entityManager->flush();

        return true;
    }
}
