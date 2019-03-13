<?php

namespace App\Service\Tmdb;

use App\Annotation\Tmdb\TmdbType;
use App\Annotation\Tmdb\TmdbField;
use App\Model\Tmdb\Actor;
use App\Model\Tmdb\Genre;
use App\Model\Tmdb\Movie;
use App\Validator\Tmdb\TmdbValidatorInterface;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\Reader;

/**
 * TMDB service used to launch request to TMDB and build entities (as PHP object) matching result.
 * This service used annotation to build response as PHP object.
 *
 * @see TmdbType
 * @see TmdbField
 */
class TmdbApiService
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var Reader
     */
    protected $annotationReader;

    /**
     * @var \GuzzleHttp\Client()
     */
    protected $guzzleClient;

    /**
     * @var array
     */
    private const URL_MAPPING = [
        'MOVIE' => 'movie',
        'TV' => 'tv',
        'PERSON' => 'person',
    ];

    /**
     * @param string $baseUri
     * @param string $apiKey
     */
    public function __construct(string $baseUri, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUri = $baseUri;
        $this->guzzleClient = new \GuzzleHttp\Client();

        // Initialize annotation reader
        // TODO : Use appropriate cache depending on environment (DEV or PROD)
        $this->annotationReader = new CachedReader(
            new AnnotationReader(),
            new ArrayCache()
        );
    }

    /**
     * Allows to get an entity from TMDB using TMDB identifier and class to build.
     *
     * @param string $entityClass class of entity to build matching specified TMDB identifier
     * @param int $tmdbId identifier of entity to build in TMDB database
     *
     * @return mixed entity matching specified class and identifier
     */
    public function getEntity(string $entityClass, int $tmdbId)
    {
        $reflectionClass = new \ReflectionClass($entityClass);
        $annotation = $this->annotationReader->getClassAnnotation($reflectionClass, TmdbType::class);

        if (null === $annotation) {
            throw new \InvalidArgumentException('Specified class doesn\'t define required information to get entity. Use annotation to fix it.');
        }

        // Build request depending on annotation information (giving type of data to get)
        $url = $this->baseUri.'/'.self::URL_MAPPING[$annotation->type].'/'.$tmdbId.'?api_key='.$this->apiKey.'&language=fr';
        $response = $this->guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        return $this->buildEntity($reflectionClass, json_decode($response->getBody(), true));
    }

    /**
     * Allows to seach entities from TMDB using search parameter (i.e. query to send) and class to build for array result.
     *
     * @param string $entityClass class of entity to build matching specified TMDB identifier
     * @param string $search query parameter to send to TMDB to search entities
     * @param TmdbValidatorInterface $validator validator used to check if entities we are searching are valide or not
     * @param int (optional) $maxCount Maximum result count allowed (default 20)
     *
     * @return array associative array with total count (key 'total') and array with first max count actors found (key 'results')
     */
    public function searchEntity(string $entityClass, string $search, TmdbValidatorInterface $validator = null, $maxCount = 20): array
    {
        $reflectionClass = new \ReflectionClass($entityClass);
        $annotation = $this->annotationReader->getClassAnnotation($reflectionClass, TmdbType::class);

        if (null === $annotation) {
            throw new \InvalidArgumentException('Specified class doesn\'t define required information to get entity. Use annotation to fix it.');
        }

        // Build request depending on annotation information (giving type of data to search)
        $url = $this->baseUri.'/search/'.self::URL_MAPPING[$annotation->type].'?query='.$search.'&api_key='.$this->apiKey.'&language=fr';
        $response = $this->guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        $jsonResponse = json_decode($response->getBody(), true);
        $result = [
            'total' => $jsonResponse['total_results'],
            'results' => [],
        ];

        // Browse reponse and build results list
        foreach ($jsonResponse['results'] as $item) {
            $entity = $this->buildEntity($reflectionClass, $item);

            if (null === $validator || $validator->isValid($entity)) {
                $result['results'][] = $entity;
                if (count($result['results']) >= $maxCount) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Allows to get filmography for specified actor identifier (TMDB).
     *
     * @param int $tmdbId identifier of actor in TMDB database
     * @param string $filmographyType 'movie' or 'tv' : allows to specifiy if we want movie credits or tv credits
     * @param string|null $entityMovieClass class of entity used to build movies (default is Movie)
     * @param TmdbValidatorInterface|null $validator validator used to check if movies are valide or not (useful to filter filmography)
     *
     * @return array list of movies of specified type
     */
    public function getFilmographyForActorId(
        int $tmdbId,
        $filmographyType = 'movie',
        string $entityMovieClass = Movie::class,
        TmdbValidatorInterface $validator = null
    ): array {
        $url = sprintf('%s/person/%d/%s_credits?api_key=%s&language=fr', $this->baseUri, $tmdbId, $filmographyType, $this->apiKey);
        $response = $this->guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        $result = [];
        $jsonResponse = json_decode($response->getBody(), true);
        $reflectionMovieClass = new \ReflectionClass($entityMovieClass);

        // Browse reponse and build filmography movie entity matching specified class
        foreach ($jsonResponse['cast'] as $movie) {
            $entity = $this->buildEntity($reflectionMovieClass, $movie);

            if (null === $validator || $validator->isValid($entity)) {
                $result[] = $entity;
            }
        }

        return $result;
    }

    /**
     * Allows to get casting for specified movie identifier (TMDB).
     *
     * @param int $tmdbId identifier of movie in TMDB database
     * @param string|null $entityActorClass class of entity used to build actors (default is Actor)
     * @param TmdbValidatorInterface|null $validator validator used to check if actors are valide or not (useful to filter casting)
     *
     * @return array list of actors of specified type
     */
    public function getCastingForMovieId(
        int $tmdbId,
        string $entityActorClass = Actor::class,
        TmdbValidatorInterface $validator = null
    ): array {
        $url = sprintf('%s/movie/%d/credits?api_key=%s&language=fr', $this->baseUri, $tmdbId, $this->apiKey);
        $response = $this->guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        $result = [];
        $jsonResponse = json_decode($response->getBody(), true);
        $reflectionActorClass = new \ReflectionClass($entityActorClass);

        // Browse reponse and build result actor list
        foreach ($jsonResponse['cast'] as $actor) {
            $entity = $this->buildEntity($reflectionActorClass, $actor);

            if (null === $validator || $validator->isValid($entity)) {
                $result[] = $entity;
            }
        }

        return $result;
    }

    /**
     * Allows to get all available genres for movies in TMDB.
     *
     * @param int $tmdbId identifier of movie in TMDB database
     * @param string|null $entityMovieClass class of entity used to build similar movies (default is Movie)
     *
     * @return array
     */
    public function getSimilarMovies(int $tmdbId, string $entityMovieClass = Movie::class): array
    {
        $url = sprintf('%s/movie/%d/similar?api_key=%s&language=fr', $this->baseUri, $tmdbId, $this->apiKey);
        $response = $this->guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        $result = [];
        $jsonResponse = json_decode($response->getBody(), true);
        $reflectionGenreClass = new \ReflectionClass($entityMovieClass);

        foreach ($jsonResponse['results'] as $similarMovie) {
            $entity = $this->buildEntity($reflectionGenreClass, $similarMovie);
            $result[] = $entity;
        }

        return $result;
    }

    /**
     * Allows to get all available genres for movies in TMDB.
     *
     * @param string|null $entityGenreClass class of entity used to build genres (default is Genre)
     *
     * @return Genre[]|array
     */
    public function getGenres(string $entityGenreClass = Genre::class): array
    {
        $url = sprintf('%s/genre/movie/list?api_key=%s&language=fr', $this->baseUri, $this->apiKey);
        $response = $this->guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getReasonPhrase(), $response->getStatusCode());
        }

        $result = [];
        $jsonResponse = json_decode($response->getBody(), true);
        $reflectionGenreClass = new \ReflectionClass($entityGenreClass);

        foreach ($jsonResponse['genres'] as $genre) {
            $entity = $this->buildEntity($reflectionGenreClass, $genre);
            $result[] = $entity;
        }

        return $result;
    }

    /**
     * Allows to build an entity from JSon flux (use JSon properties to fill properties of instancied class).
     *
     * @param \ReflectionClass $reflectionClass reflection class of entity to build using specified JSon flux
     * @param array $jsonFlux JSon array containing information to build entity
     *
     * @return mixed entity matching specified reflection class filled with data from specified JSon flux
     */
    protected function buildEntity(\ReflectionClass $reflectionClass, array $jsonFlux)
    {
        $propertyList = $reflectionClass->getProperties();
        $tmdbEntity = $reflectionClass->newInstance();

        // Browse all properties of entity to build and initialize them if needed (i.e. property mapped with TMDB using annotation)
        foreach ($propertyList as $property) {
            $annotation = $this->annotationReader->getPropertyAnnotation($property, TmdbField::class);

            // If property is mapped with TMDB property and matching TMDB property initialzed => process it
            if (null !== $annotation && isset($jsonFlux[$annotation->name])) {
                $value = $this->processValueForAnnotation($jsonFlux[$annotation->name], $annotation);
                $property->setAccessible(true);
                $property->setValue($tmdbEntity, $value);
            }
        }

        return $tmdbEntity;
    }

    /**
     * Allows to convert specified value to match with annotation informations.
     *
     * @param mixed $value value from JSon to cast
     * @param TmdbField $annotation
     *
     * @return mixed
     */
    protected function processValueForAnnotation($value, TmdbField $annotation)
    {
        if (!isset($annotation->type)) {
            return $value;
        }

        switch ($annotation->type) {
            case 'string':
                return strval($value);
            case 'integer':
                return intval($value);
            case 'float':
                return floatval($value);
            case 'datetime':
                return \DateTime::createFromFormat($annotation->dateFormat, $value) ?: null;
            case 'array':
                return is_array($value) ? $value : [$value];
            case 'object':
                return $this->processObjectValueForAnnotation($value, $annotation);
            case 'object_array':
                return $this->processObjectArrayValueForAnnotation($value, $annotation);
            // NOTE : Should never occurs
            default:
                return $value;
        }
    }

    /**
     * Allows to build TMDB sub entity value from JSon.
     *
     * @param array $objectValue JSon array used to build entity
     * @param TmdbField $annotation annotation indicating sub entity class to build
     *
     * @return mixed entity initialized with JSon data
     */
    protected function processObjectValueForAnnotation(array $objectValue, TmdbField $annotation)
    {
        if (null === $objectValue) {
            return null;
        }

        return $this->buildEntity(new \ReflectionClass($annotation->subClass), $objectValue);
    }

    /**
     * Allows to build array value from JSon with sub TMDB entities.
     *
     * @param array $value JSon array used to build entities array
     * @param TmdbField $annotation annotation indicating sub entity class to build to fill array
     *
     * @return array result containing sub entities initialized with JSon data
     */
    protected function processObjectArrayValueForAnnotation(array $arrayValue, TmdbField $annotation): array
    {
        $reflectionClass = new \ReflectionClass($annotation->subClass);
        $resultArray = [];

        foreach ($arrayValue as $value) {
            $subEntity = $this->buildEntity($reflectionClass, $value);
            $resultArray[] = $subEntity;
        }

        return $resultArray;
    }
}
