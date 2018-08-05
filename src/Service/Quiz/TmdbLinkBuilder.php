<?php

namespace App\Service\Quiz;

use App\Event\QuizEvents;
use App\Event\Quiz\TmdbLinkProgressEvent;
use App\Event\Quiz\TmdbLinkStartEvent;
use App\Model\Tmdb\Movie;
use App\Repository\Quiz\ResponseRepository;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to create link between responses of quiz (i.e. movies) and TMDB.
 * => setting TMDB identifier for responses of quiz.
 */
class TmdbLinkBuilder
{
    /**
     * @var ResponseRepository
     */
    protected $responseRepository;

    /**
     * @var TmdbApiService
     */
    protected $tmdbService;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param ResponseRepository $responseRepository
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ResponseRepository $responseRepository,
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->responseRepository = $responseRepository;
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function build(int $quizId)
    {
        $processCount = 0;
        $responses = $this->responseRepository->findResponsesForQuizId($quizId);

        $this->eventDispatcher->dispatch(QuizEvents::BUILD_TMDB_LINK_START, new TmdbLinkStartEvent(count($responses)));

        /** @var Response $response */
        foreach ($responses as $response) {
            $movies = $this->tmdbService->searchEntity(Movie::class, $response->getTitle());

            if ($movies['total'] > 0) {
                $tmdbId = $movies['result'][0]->getTmdbId();
                $movie = $this->tmdbService->getEntity(Movie::class, $tmdbId);

                $response->setTmdbId($tmdbId);
                $response->setPictureUrl($movie->getPictureUrl());
                $response->setTagline($movie->getTagline());
                $response->setOverview($movie->getOverview());

                $this->entityManager->persist($response);
                $this->entityManager->flush();
            }

            // WARNING : wait between each TMDB request to not override request rate limit (40 per seconde)
            usleep(600000);

            $this->eventDispatcher->dispatch(QuizEvents::BUILD_TMDB_LINK_PROGRESS, new TmdbLinkProgressEvent(++$processCount));
        }

        $this->eventDispatcher->dispatch(QuizEvents::BUILD_TMDB_LINK_END);
    }
}
