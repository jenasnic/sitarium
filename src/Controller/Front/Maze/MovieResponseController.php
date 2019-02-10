<?php

namespace App\Controller\Front\Maze;

use App\Repository\Maze\MovieRepository;
use App\Service\Maze\MoviePathResponseValidator;
use App\Tool\TmdbUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MovieResponseController extends Controller
{
    /**
     * @Route("/quiz-casting/ajax/valider-response", name="fo_maze_movie_progress", methods="POST")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param MoviePathResponseValidator $responseChecker
     *
     * @return JsonResponse
     */
    public function progressAction(
        Request $request,
        TranslatorInterface $translator,
        MoviePathResponseValidator $responseChecker
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $previousMovieId = $data['currentTmdbId'];
        $nextMovieId = $data['nextTmdbId'];
        $actorName = trim($data['response']);

        try {
            if ($commonActor = $responseChecker->check($previousMovieId, $nextMovieId, $actorName)) {
                return new JsonResponse([
                    'success' => true,
                    'displayName' => $commonActor->getFullname(),
                    'tmdbLink' => sprintf('https://www.themoviedb.org/person/%d', $commonActor->getTmdbId()),
                    'pictureUrl' => TmdbUtil::getBasePictureUrl().$commonActor->getPictureUrl(),
                ]);
            }

            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.response.incorrect')]);
        } catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.response.process_error')]);
        }
    }

    /**
     * @Route("/quiz-casting/ajax/indice", name="fo_maze_movie_trick", methods="POST")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param MovieRepository $movieRepository
     *
     * @return JsonResponse
     */
    public function trickAction(
        Request $request,
        TranslatorInterface $translator,
        MovieRepository $movieRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $movieId = $data['tmdbId'];

        try {
            $movie = $movieRepository->find($movieId);

            if ($movie) {
                $actors = [];
                foreach ($movie->getActors() as $actor) {
                    $actors[] = $actor->getFullname();
                }

                return new JsonResponse(['success' => true, 'responses' => $actors]);
            } else {
                return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.movie.not_found')]);
            }
        } catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.maze.movie.casting_error')]);
        }
    }
}
