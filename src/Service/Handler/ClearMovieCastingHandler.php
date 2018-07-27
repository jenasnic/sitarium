<?php

namespace App\Service\Handler;

use App\Repository\Maze\CastingActorRepository;
use App\Repository\Maze\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class allows to clear casting, i.e. remove all actors relative to existing movies.
 */
class ClearMovieCastingHandler
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
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param MovieRepository $movieRepository
     * @param CastingActorRepository $actorRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        MovieRepository $movieRepository,
        CastingActorRepository $actorRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->movieRepository = $movieRepository;
        $this->actorRepository = $actorRepository;
        $this->entityManager = $entityManager;
    }

    public function handle()
    {
        $actors = $this->actorRepository->findAll();
        foreach ($actors as $actor) {
            $this->entityManager->remove($actor);
        }

        $this->movieRepository->resetMoviesStatus();
        $this->entityManager->flush();
    }
}
