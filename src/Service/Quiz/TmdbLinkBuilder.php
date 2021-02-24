<?php

namespace App\Service\Quiz;

use App\Entity\Quiz\Response;
use App\Event\Quiz\TmdbLinkEndEvent;
use App\Event\Quiz\TmdbLinkErrorEvent;
use App\Event\Quiz\TmdbLinkProgressEvent;
use App\Event\Quiz\TmdbLinkStartEvent;
use App\Repository\Quiz\QuizRepository;
use App\Service\Tmdb\TmdbDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This class allows to create link between responses of quiz (i.e. movies) and TMDB.
 * => setting TMDB identifier for responses of quiz.
 */
class TmdbLinkBuilder
{
    protected QuizRepository $quizRepository;

    protected TmdbDataProvider $tmdbDataProvider;

    protected EntityManagerInterface $entityManager;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        QuizRepository $quizRepository,
        TmdbDataProvider $tmdbDataProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->quizRepository = $quizRepository;
        $this->tmdbDataProvider = $tmdbDataProvider;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param int $quizId
     */
    public function build(int $quizId): void
    {
        $processCount = 0;
        $responses = $this->quizRepository->find($quizId)->getResponses();

        $this->eventDispatcher->dispatch(new TmdbLinkStartEvent(count($responses)), TmdbLinkStartEvent::BUILD_TMDB_LINK_START);

        try {
            /** @var Response $response */
            foreach ($responses as $response) {
                $movies = $this->tmdbDataProvider->searchMovies($response->getTitle());
                // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
                usleep(250001);

                if (count($movies) > 0) {
                    $tmdbId = $movies[0]->getId();
                    $movie = $this->tmdbDataProvider->getMovie($tmdbId);
                    // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
                    usleep(250001);

                    $response->setTmdbId($tmdbId);
                    $response->setPictureUrl($movie->getPosterPath());
                    $response->setTagline($movie->getTagline());
                    $response->setOverview($movie->getOverview());

                    $this->entityManager->persist($response);
                    $this->entityManager->flush();
                }

                $this->eventDispatcher->dispatch(new TmdbLinkProgressEvent(++$processCount), TmdbLinkProgressEvent::BUILD_TMDB_LINK_PROGRESS);
            }

            $this->eventDispatcher->dispatch(new TmdbLinkEndEvent(), TmdbLinkEndEvent::BUILD_TMDB_LINK_END);

        } catch (Exception $e) {
            $this->eventDispatcher->dispatch(new TmdbLinkErrorEvent($e), TmdbLinkErrorEvent::BUILD_TMDB_LINK_ERROR);
        }
    }
}
