<?php

namespace App\Service\Handler\Tagline;

use App\Domain\Command\Tagline\AddMoviesCommand;
use App\Repository\Tagline\GenreRepository;
use App\Repository\Tagline\MovieRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movies from TMDB in current database.
 */
class AddMoviesHandler
{
    /**
     * @var TmdbDataProvider
     */
    protected $tmdbDataProvider;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var MovieRepository
     */
    protected $movieRepository;

    /**
     * @var GenreRepository
     */
    protected $genreRepository;

    /**
     * @param TmdbDataProvider $tmdbDataProvider
     * @param EntityManagerInterface $entityManager
     * @param MovieRepository $movieRepository
     * @param GenreRepository $genreRepository
     */
    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        MovieRepository $movieRepository,
        GenreRepository $genreRepository
    ) {
        $this->movieRepository = $movieRepository;
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->entityManager = $entityManager;
        $this->genreRepository = $genreRepository;
    }

    /**
     * @param AddMoviesCommand $command
     */
    public function handle(AddMoviesCommand $command)
    {
        foreach ($command->getTmdbIds() as $tmdbId) {
            if (null !== $this->movieRepository->find($tmdbId)) {
                continue;
            }

            $tmdbMovie = $this->tmdbDataProvider->getMovie($tmdbId);
            if (empty($tmdbMovie->getTagline())) {
                continue;
            }

            foreach ($tmdbMovie->getTmdbGenres() as $tmdbGenre) {
                $genre = $this->genreRepository->find($tmdbGenre->getTmdbId());
                if (null !== $genre) {
                    $tmdbMovie->addGenre($genre);
                }
            }

            $this->entityManager->persist($tmdbMovie);
        }

        $this->entityManager->flush();
    }
}
