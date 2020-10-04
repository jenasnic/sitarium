<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\FilmographyMovie;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Entity\Maze\Movie;

class FilmographyMovieSynchronizer extends AbstractSynchronizer
{
    /**
     * @var FilmographyMovieRepository
     */
    protected $filmographyMovieRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param FilmographyMovieRepository $filmographyMovieRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        FilmographyMovieRepository $filmographyMovieRepository
    ) {
        parent::__construct($tmdbService, $entityManager, $eventDispatcher);

        $this->filmographyMovieRepository = $filmographyMovieRepository;
    }

    /**
     * @return string
     */
    protected function getLocalEntityClass()
    {
        return FilmographyMovie::class;
    }

    /**
     * @return string
     */
    protected function getTmdbEntityClass()
    {
        return Movie::class;
    }

    /**
     * @return DisplayableInterface[]|array
     */
    protected function getAllData()
    {
        return $this->filmographyMovieRepository->findAll();
    }

    /**
     * @param FilmographyMovie $localData
     * @param Movie $tmdbData
     *
     * @return bool TRUE if local data is updated, FALSE either
     */
    protected function synchronizeData($localData, $tmdbData): bool
    {
        if ($localData->getPictureUrl() !== $tmdbData->getPictureUrl()) {
            $localData->setPictureUrl($tmdbData->getPictureUrl());

            return true;
        }

        return false;
    }
}
