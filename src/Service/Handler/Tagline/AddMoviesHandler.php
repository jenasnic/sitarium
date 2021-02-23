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
    protected TmdbDataProvider $tmdbDataProvider;

    protected EntityManagerInterface $entityManager;

    protected MovieRepository $movieRepository;

    protected GenreRepository $genreRepository;

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

    public function handle(AddMoviesCommand $command): void
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
