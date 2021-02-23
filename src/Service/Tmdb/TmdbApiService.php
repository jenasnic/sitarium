<?php

namespace App\Service\Tmdb;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * TMDB service used to launch request to TMDB and build models (as PHP object) matching result.
 */
class TmdbApiService
{
    protected string $apiKey;

    protected string $baseUri;

    protected HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient, string $baseUri, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUri = $baseUri;
        $this->httpClient = $httpClient;
    }

    /**
     * @return array<string, mixed>
     */
    public function getActor(int $tmdbId): array
    {
        $url = $this->decorateUri('/person/'.$tmdbId);

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMovie(int $tmdbId): array
    {
        $url = $this->decorateUri('/movie/'.$tmdbId);

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function getGenres(): array
    {
        $url = $this->decorateUri('/genre/movie/list');

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function searchActors(string $search): array
    {
        $url = $this->decorateUri('/search/person?query='.$search);

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function searchMovies(string $search): array
    {
        $url = $this->decorateUri('/search/movie?query='.$search);

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function getFilmography(int $tmdbId): array
    {
        $url = $this->decorateUri('/person/'.$tmdbId.'/movie_credits');

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCasting(int $tmdbId): array
    {
        $url = $this->decorateUri('/movie/'.$tmdbId.'/credits');

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSimilarMovies(int $tmdbId): array
    {
        $url = $this->decorateUri('/movie/'.$tmdbId.'/similar');

        return $this->httpClient
            ->request('GET', $url)
            ->toArray()
        ;
    }

    protected function decorateUri(string $uri, string $language = 'fr'): string
    {
        $connector = (false === strpos($uri, '?')) ? '?' : '&';

        return $this->baseUri.$uri.$connector.'api_key='.$this->apiKey.'&language='.$language;
    }
}
