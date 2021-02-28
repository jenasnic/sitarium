<?php

namespace App\Service\Tagline;

use App\Model\Tmdb\Genre;
use App\Repository\Tagline\GenreRepository;
use App\Service\Converter\GenreConverter;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class allows to synchronize genre with TMDB.
 */
class GenreSynchronizer
{
    protected GenreRepository $genreRepository;

    protected TmdbDataProvider $tmdbDataProvider;

    protected GenreConverter $genreConverter;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        GenreRepository $genreRepository,
        TmdbDataProvider $tmdbDataProvider,
        GenreConverter $genreConverter,
        EntityManagerInterface $entityManager
    ) {
        $this->genreRepository = $genreRepository;
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->genreConverter = $genreConverter;
        $this->entityManager = $entityManager;
    }

    public function synchronize(): void
    {
        $tmdbGenres = $this->tmdbDataProvider->getGenres();

        /* @var Genre $tmdbGenre */
        foreach ($tmdbGenres as $tmdbGenre) {
            if (null === $this->genreRepository->find($tmdbGenre->getId())) {
                $genre = $this->genreConverter->convert($tmdbGenre);
                $this->entityManager->persist($genre);
            }
        }

        $this->entityManager->flush();
    }
}
