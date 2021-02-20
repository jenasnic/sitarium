<?php

namespace App\Service\Handler\Maze;

use App\Domain\Command\Maze\AddMovieCommand;
use App\Repository\Maze\MovieRepository;
use App\Service\Converter\TmdbMovieConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movie from TMDB in current database.
 */
class AddMovieHandler
{
    /**
     * @var TmdbDataProvider
     */
    protected $tmdbDataProvider;

    /**
     * @var TmdbMovieConverter
     */
    protected $tmdbMovieConverter;

    /**
     * @var MovieRepository
     */
    protected $movieRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param TmdbMovieConverter $tmdbMovieConverter
     * @param MovieRepository $movieRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        TmdbMovieConverter $tmdbMovieConverter,
        MovieRepository $movieRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->movieRepository = $movieRepository;
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->tmdbMovieConverter = $tmdbMovieConverter;
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddMovieCommand $command
     */
    public function handle(AddMovieCommand $command)
    {
        // Check if movie already exist
        if (null !== $this->movieRepository->find($command->getTmdbId())) {
            return;
        }

        $tmdbMovie = $this->tmdbDataProvider->getMovie($command->getTmdbId());
        $movieToAdd = $this->tmdbMovieConverter->convert($tmdbMovie);

        $this->entityManager->persist($movieToAdd);
        $this->entityManager->flush();
    }
}
