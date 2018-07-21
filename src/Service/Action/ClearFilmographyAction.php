<?php

namespace App\Service\Action;

use App\Repository\Maze\ActorRepository;
use App\Repository\Maze\FilmographyMovieRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class allows to clear filmography, i.e. remove all movies relative to existing actors.
 */
class ClearFilmographyAction
{
    /**
     * @var ActorRepository
     */
    protected $actorRepository;

    /**
     * @var FilmographyMovieRepository
     */
    protected $movieRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param ActorRepository $actorRepository
     * @param FilmographyMovieRepository $movieRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ActorRepository $actorRepository,
        FilmographyMovieRepository $movieRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->actorRepository = $actorRepository;
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
    }

    public function execute()
    {
        $movies = $this->movieRepository->findAll();
        foreach ($movies as $movie) {
            $this->entityManager->remove($movie);
        }

        $this->actorRepository->resetActorStatus();
        $this->entityManager->flush();
    }
}
