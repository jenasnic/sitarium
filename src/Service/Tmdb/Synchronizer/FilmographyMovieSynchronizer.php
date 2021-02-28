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

    protected function getType(): string
    {
        return FilmographyMovie::class;
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

        $isUpdated = false;

        if ($data->getPictureUrl() !== $tmdbData->getPosterPath()) {
            $data->setPictureUrl($tmdbData->getPosterPath());
            $isUpdated = true;
        }

        if ($data->getTitle() !== $tmdbData->getTitle()) {
            $data->setTitle($tmdbData->getTitle());
            $isUpdated = true;
        }

        if ($data->getVoteCount() !== $tmdbData->getVoteCount()) {
            $data->setVoteCount($tmdbData->getVoteCount());
            $isUpdated = true;
        }

        return $isUpdated;
    }
}
