<?php

namespace App\Service\Tagline;

use App\Entity\Tagline\Genre;
use App\Repository\Tagline\GenreRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This class allows to synchronize genre with TMDB.
 */
class GenreSynchronizer
{
    protected GenreRepository $genreRepository;

    // @todo : use TmdbDataProvider
    protected TmdbApiService $tmdbService;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        GenreRepository $genreRepository,
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager
    ) {
        $this->genreRepository = $genreRepository;
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
    }

    public function synchronize(): void
    {
        $tmdbGenres = $this->tmdbService->getGenres(Genre::class);

        /* @var Genre $tmdbGenre */
        foreach ($tmdbGenres as $tmdbGenre) {
            $localGenre = $this->genreRepository->find($tmdbGenre->getTmdbId());
            if (null === $localGenre) {
                $this->entityManager->persist($tmdbGenre);
            }
        }

        $this->entityManager->flush();
    }
}
