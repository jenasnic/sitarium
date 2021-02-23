<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\FilmographyMovie;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @extends AbstractSynchronizer<FilmographyMovie>
 */
class FilmographyMovieSynchronizer extends AbstractSynchronizer
{
    protected FilmographyMovieRepository $filmographyMovieRepository;

    public function __construct(
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        FilmographyMovieRepository $filmographyMovieRepository
    ) {
        parent::__construct($tmdbDataProvider, $entityManager, $eventDispatcher);

        $this->filmographyMovieRepository = $filmographyMovieRepository;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return FilmographyMovie::class === $type;
    }

    /**
     * @return FilmographyMovie[]|array<FilmographyMovie>
     */
    protected function getAllData(): array
    {
        return $this->filmographyMovieRepository->findAll();
    }

    /**
     * @param FilmographyMovie $data
     *
     * @return bool TRUE if local data is updated, FALSE either
     */
    protected function synchronizeData($data): bool
    {
        $tmdbData = $this->tmdbDataProvider->getMovie($data->getTmdbId());

        if ($data->getPictureUrl() !== $tmdbData->getProfilePath()) {
            $data->setPictureUrl($tmdbData->getProfilePath());

            return true;
        }

        return false;
    }
}
