<?php

namespace App\Controller\Front\Maze;

use App\Repository\Maze\MovieRepository;
use App\Service\Maze\MoviePathResponseValidator;
use App\Tool\TmdbUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieResponseController extends Controller
{
    /**
     * @Route("/quiz-casting/ajax/valider-response", name="fo_maze_movie_progress", methods="POST")
     *
     * @param Request $request
     * @param MoviePathResponseValidator $responseChecker
     *
     * @return JsonResponse
     */
    public function progressAction(Request $request, MoviePathResponseValidator $responseChecker): JsonResponse
    {
        $previousMovieId = $request->request->get('previousMovieId');
        $nextMovieId = $request->request->get('nextMovieId');
        $actorName = trim(rawurldecode($request->request->get('response')));

        try {
            if ($commonActor = $responseChecker->execute($previousMovieId, $nextMovieId, $actorName)) {
                return new JsonResponse([
                    'success' => true,
                    'tmdbId' => $commonActor->getTmdbId(),
                    'fullname' => $commonActor->getFullname(),
                    'pictureUrl' => TmdbUtil::getBasePictureUrl() . $commonActor->getPictureUrl(),
                ]);
            }

            return new JsonResponse(['success' => false, 'message' => 'Réponse incorrecte.']);
        }
        catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => 'Impossible de vérifier votre réponse.']);
        }
    }

    /**
     * @Route("/quiz-casting/ajax/indice", name="fo_maze_movie_trick", methods="POST")
     *
     * @param MovieRepository $movieRepository
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function trickAction(MovieRepository $movieRepository, Request $request): JsonResponse
    {
        $movieId = $request->request->get('movieId');

        try {
            $movie = $movieRepository->find($movieId);

            if ($movie) {
                $htmlFlux = $this->renderView('front/maze/movie/trick.html.twig', ['movie' => $movie]);

                return new JsonResponse(['success' => true, 'message' => $htmlFlux]);
            }
            else
                return new JsonResponse(['success' => false, 'message' => 'Film introuvable.']);
        }
        catch (\Exception $ex) {
            return new JsonResponse(array('success' => false, 'message' => 'Impossible de charger le casting du film.'));
        }
    }
}
