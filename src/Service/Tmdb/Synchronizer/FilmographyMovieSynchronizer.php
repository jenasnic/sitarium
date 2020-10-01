<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Entity\Maze\FilmographyMovie;
use App\Model\Tmdb\Search\DisplayableInterface;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

class FilmographyMovieSynchronizer extends AbstractSynchronizer
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
     * @var FilmographyMovieRepository
     */
    protected $filmographyMovieRepository;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param FilmographyMovieRepository $filmographyMovieRepository
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        FilmographyMovieRepository $filmographyMovieRepository
    ) {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->filmographyMovieRepository = $filmographyMovieRepository;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return FilmographyMovie::class;
    }

    /**
     * @return DisplayableInterface[]|array
     */
    protected function getAllData()
    {
        return $this->actorRepository->findAll();
    }

    /**
     * @param FilmographyMovie $localData
     * @param FilmographyMovie $tmdbData
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
