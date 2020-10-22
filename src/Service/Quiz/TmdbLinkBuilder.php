<?php

namespace App\Service\Quiz;

use App\Entity\Quiz\Response;
use App\Event\QuizEvents;
use App\Event\Quiz\TmdbLinkProgressEvent;
use App\Event\Quiz\TmdbLinkStartEvent;
use App\Model\Tmdb\Movie;
use App\Repository\Quiz\QuizRepository;
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
     * @var QuizRepository
     */
    protected $quizRepository;

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
     * @param QuizRepository $quizRepository
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        QuizRepository $quizRepository,
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->quizRepository = $quizRepository;
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param int $quizId
     */
    public function build(int $quizId)
    {
        $processCount = 0;
        $responses = $this->quizRepository->find($quizId)->getResponses();

        $this->eventDispatcher->dispatch(QuizEvents::BUILD_TMDB_LINK_START, new TmdbLinkStartEvent(count($responses)));

        /** @var Response $response */
        foreach ($responses as $response) {
            $movies = $this->tmdbService->searchEntity(Movie::class, $response->getTitle());
            // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
            usleep(250001);

            if ($movies['total'] > 0) {
                $tmdbId = $movies['results'][0]->getTmdbId();
                $movie = $this->tmdbService->getEntity(Movie::class, $tmdbId);
                // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
                usleep(250001);

                $response->setTmdbId($tmdbId);
                $response->setPictureUrl($movie->getPictureUrl());
                $response->setTagline($movie->getTagline());
                $response->setOverview($movie->getOverview());

                $this->entityManager->persist($response);
                $this->entityManager->flush();
            }

            $this->eventDispatcher->dispatch(QuizEvents::BUILD_TMDB_LINK_PROGRESS, new TmdbLinkProgressEvent(++$processCount));
        }

        $this->eventDispatcher->dispatch(QuizEvents::BUILD_TMDB_LINK_END);
    }
}
