<?php

namespace App\Service\Handler\Maze;

use App\Domain\Command\Maze\AddMovieCommand;
use App\Repository\Maze\MovieRepository;
use App\Service\Converter\MovieConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movie from TMDB in current database.
 */
class AddMovieHandler
{
    protected TmdbDataProvider $tmdbDataProvider;

    protected MovieConverter $movieConverter;

    protected MovieRepository $movieRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        MovieConverter $movieConverter,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->movieRepository = $movieRepository;
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->movieConverter = $movieConverter;
        $this->entityManager = $entityManager;
    }

    public function handle(AddMovieCommand $command): void
    {
        // Check if movie already exist
        if (null !== $this->movieRepository->find($command->getTmdbId())) {
            return;
        }

        $tmdbMovie = $this->tmdbDataProvider->getMovie($command->getTmdbId());
        $movieToAdd = $this->movieConverter->convert($tmdbMovie);

        $this->entityManager->persist($movieToAdd);
        $this->entityManager->flush();
    }
}
