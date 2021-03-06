<?php

namespace App\Controller\Front\Maze;

use App\Repository\Maze\ActorRepository;
use App\Repository\Maze\FilmographyMovieRepository;
use App\Service\Maze\ActorPathResponseValidator;
use App\Tool\TmdbUtil;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActorResponseController extends AbstractController
{
    /**
     * @Route("/quiz-filmographie/ajax/valider-response", name="fo_maze_actor_progress", methods="POST")
     */
    public function progressAction(
        Request $request,
        TranslatorInterface $translator,
        ActorPathResponseValidator $responseChecker
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $previousActorId = $data['currentTmdbId'];
        $nextActorId = $data['nextTmdbId'];
        $movieTitle = trim($data['response']);

        try {
            if ($commonMovie = $responseChecker->check($previousActorId, $nextActorId, $movieTitle)) {
                return new JsonResponse([
                    'success' => true,
                    'displayName' => $commonMovie->getTitle(),
                    'tmdbLink' => sprintf('https://www.themoviedb.org/movie/%d', $commonMovie->getTmdbId()),
                    'pictureUrl' => TmdbUtil::getBasePictureUrl().$commonMovie->getPictureUrl(),
                ]);
            }

            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.response.incorrect')]);
        } catch (Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.response.process_error')]);
        }
    }

    /**
     * @Route("/quiz-filmographie/ajax/indice", name="fo_maze_actor_trick", methods="POST")
     */
    public function trickAction(
        Request $request,
        TranslatorInterface $translator,
        ActorRepository $actorRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $actorId = $data['tmdbId'];
        $level = $data['level'];

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);
            $actor = $actorRepository->find($actorId);

            if ($actor) {
                $movies = [];
                foreach ($actor->getMovies() as $movie) {
                    if ($movie->getVoteCount() >= $minVoteCount) {
                        $movies[] = $movie->getTitle();
                    }
                }

                return new JsonResponse(['success' => true, 'responses' => $movies]);
            }

            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.actor.not_found')]);
        } catch (Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.actor.filmography_error')]);
        }
    }

    /**
     * @Route("/quiz-filmographie/ajax/tricher", name="fo_maze_actor_cheat", methods="POST")
     */
    public function cheatAction(
        Request $request,
        TranslatorInterface $translator,
        ActorRepository $actorRepository,
        FilmographyMovieRepository $filmographyMovieRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $currentActorId = $data['currentTmdbId'];
        $nextActorId = $data['nextTmdbId'];

        try {
            $currentActor = $actorRepository->find($currentActorId);
            $nextActor = $actorRepository->find($nextActorId);

            $commonMovie = $filmographyMovieRepository->findTopMovieWithActors($currentActor, $nextActor);

            if (null === $commonMovie) {
                return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.actor.cheat.not_found')]);
            }

            return new JsonResponse([
                'success' => true,
                'displayName' => $commonMovie->getTitle(),
                'tmdbLink' => sprintf('https://www.themoviedb.org/movie/%d', $commonMovie->getTmdbId()),
                'pictureUrl' => TmdbUtil::getBasePictureUrl().$commonMovie->getPictureUrl(),
            ]);
        } catch (Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.actor.cheat.error')]);
        }
    }
}
