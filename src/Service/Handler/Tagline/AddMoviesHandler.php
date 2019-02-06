<?php

namespace App\Service\Handler\Tagline;

use App\Domain\Command\Tagline\AddMoviesCommand;
use App\Entity\Tagline\Movie;
use App\Repository\Tagline\GenreRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to add movies from TMDB in current database.
 */
class AddMoviesHandler
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
     * @var GenreRepository
     */
    protected $genreRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param GenreRepository $genreRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        GenreRepository $genreRepository
    ) {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->genreRepository = $genreRepository;
    }

    /**
     * @param AddMoviesCommand $command
     */
    public function handle(AddMoviesCommand $command)
    {
        foreach ($command->getTmdbIds() as $tmdbId) {
            if (null !== $this->entityManager->getRepository(Movie::class)->find($tmdbId)) {
                continue;
            }

            /* @var Movie $movieToAdd */
            $movieToAdd = $this->tmdbService->getEntity(Movie::class, $tmdbId);
            if (empty($movieToAdd->getTagline())) {
                continue;
            }

            foreach ($movieToAdd->getTmdbGenres() as $tmdbGenre) {
                $genre = $this->genreRepository->find($tmdbGenre->getTmdbId());
                if (null !== $genre) {
                    $movieToAdd->addGenre($genre);
                }
            }

            $this->entityManager->persist($movieToAdd);
        }

        $this->entityManager->flush();
    }
}
