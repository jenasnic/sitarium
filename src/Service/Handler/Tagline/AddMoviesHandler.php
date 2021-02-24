<?php

namespace App\Service\Handler\Tagline;

use App\Domain\Command\Tagline\AddMoviesCommand;
use App\Repository\Tagline\GenreRepository;
use App\Repository\Tagline\MovieRepository;
use App\Service\Converter\TaglineMovieConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movies from TMDB in current database.
 */
class AddMoviesHandler
{
    protected TmdbDataProvider $tmdbDataProvider;

    protected TaglineMovieConverter $taglineMovieConverter;

    protected EntityManagerInterface $entityManager;

    protected MovieRepository $movieRepository;

    protected GenreRepository $genreRepository;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        TaglineMovieConverter $taglineMovieConverter,
        EntityManagerInterface $entityManager,
        MovieRepository $movieRepository,
        GenreRepository $genreRepository
    ) {
        $this->movieRepository = $movieRepository;
        $this->taglineMovieConverter = $taglineMovieConverter;
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

            $movie = $this->taglineMovieConverter->convert($tmdbMovie);
            foreach ($tmdbMovie->getGenres() as $tmdbGenre) {
                $genre = $this->genreRepository->find($tmdbGenre->getId());
                if (null !== $genre) {
                    $movie->addGenre($genre);
                }
            }

            $this->entityManager->persist($movie);
        }

        $this->entityManager->flush();
    }
}
