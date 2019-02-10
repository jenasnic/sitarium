<?php

namespace App\Controller\Front\Maze;

use App\Repository\Maze\ActorRepository;
use App\Service\Maze\ActorPathResponseValidator;
use App\Tool\TmdbUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActorResponseController extends Controller
{
    /**
     * @Route("/quiz-filmographie/ajax/valider-response", name="fo_maze_actor_progress", methods="POST")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param ActorPathResponseValidator $responseChecker
     *
     * @return JsonResponse
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
        } catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.response.process_error')]);
        }
    }

    /**
     * @Route("/quiz-filmographie/ajax/indice", name="fo_maze_actor_trick", methods="POST")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param ActorRepository $actorRepository
     *
     * @return JsonResponse
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
            } else {
                return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.actor.not_found')]);
            }
        } catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.actor.filmography_error')]);
        }
    }
}
