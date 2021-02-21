<?php

namespace App\Service\Tmdb;

use App\Model\Tmdb\Actor;
use App\Model\Tmdb\Genre;
use App\Model\Tmdb\Movie;
use App\Validator\Tmdb\TmdbValidatorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * This service allows to provide data from TMDB.
 */
class TmdbDataProvider
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbApiService;

    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;

    public function __construct(TmdbApiService $tmdbApiService, DenormalizerInterface $denormalizer)
    {
        $this->tmdbApiService = $tmdbApiService;
        $this->denormalizer = $denormalizer;
    }

    public function getActor(int $tmdbId): Actor
    {
        $data = $this->tmdbApiService->getActor($tmdbId);

        return $this->denormalizer->denormalize($data, Actor::class);
    }

    public function getMovie(int $tmdbId): Movie
    {
        $data = $this->tmdbApiService->getMovie($tmdbId);

        return $this->denormalizer->denormalize($data, Movie::class);
    }

    public function getGenres(): array
    {
        $data = $this->tmdbApiService->getGenres();

        return $this->buildArray($data['results'], Genre::class);
    }

    public function searchActors(string $search, TmdbValidatorInterface $validator = null, int $maxCount = 10)
    {
        $data = $this->tmdbApiService->searchActors($search);

        return $this->buildArray($data['results'], Actor::class, $validator, $maxCount);
    }

    public function searchMovies(string $search, TmdbValidatorInterface $validator = null, int $maxCount = 10)
    {
        $data = $this->tmdbApiService->searchMovies($search);

        return $this->buildArray($data['results'], Movie::class, $validator, $maxCount);
    }

    public function getFilmography(int $actorId): array
    {
        $data = $this->tmdbApiService->getFilmography($actorId);

        return $this->buildArray($data['cast'], Movie::class);
    }

    public function getCasting(int $movieId): array
    {
        $data = $this->tmdbApiService->getCasting($movieId);

        return $this->buildArray($data['cast'], Actor::class);
    }

    public function getSimilarMovies(int $movieId): array
    {
        $data = $this->tmdbApiService->getSimilarMovies($movieId);

        return $this->buildArray($data['results'], Movie::class);
    }

    protected function buildArray(array $data, string $className, TmdbValidatorInterface $validator = null, $maxCount = null): array
    {
        $result = [];

        foreach ($data as $item) {
            $entity = $this->denormalizer->denormalize($item, $className);

            if (null === $validator || $validator->isValid($entity)) {
                $result[] = $entity;
                if (null !== $maxCount && count($result) >= $maxCount) {
                    break;
                }
            }
        }

        return $result;
    }
}
