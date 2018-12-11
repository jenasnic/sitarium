<?php

namespace App\Controller\Front\Maze;

use App\Repository\Maze\ActorRepository;
use App\Service\Maze\ActorPathResponseValidator;
use App\Tool\TmdbUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActorResponseController extends Controller
{
    /**
     * @Route("/quiz-filmographie/ajax/valider-response", name="fo_maze_actor_progress", methods="POST")
     *
     * @param Request $request
     * @param ActorPathResponseValidator $responseChecker
     *
     * @return JsonResponse
     */
    public function progressAction(Request $request, ActorPathResponseValidator $responseChecker): JsonResponse
    {
        $previousActorId = $request->request->get('previousActorId');
        $nextActorId = $request->request->get('nextActorId');
        $movieTitle = trim(rawurldecode($request->request->get('response')));

        try {
            if ($commonMovie = $responseChecker->execute($previousActorId, $nextActorId, $movieTitle)) {
                return new JsonResponse([
                    'success' => true,
                    'tmdbId' => $commonMovie->getTmdbId(),
                    'title' => $commonMovie->getTitle(),
                    'pictureUrl' => TmdbUtil::getBasePictureUrl().$commonMovie->getPictureUrl(),
                ]);
            }

            return new JsonResponse(['success' => false, 'message' => 'Réponse incorrecte.']);
        } catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => 'Impossible de vérifier votre réponse.']);
        }
    }

    /**
     * @Route("/quiz-filmographie/ajax/indice", name="fo_maze_actor_trick", methods="POST")
     *
     * @param Request $request
     * @param ActorRepository $actorRepository
     *
     * @return JsonResponse
     */
    public function trickAction(Request $request, ActorRepository $actorRepository): JsonResponse
    {
        $actorId = $request->request->get('actorId');
        $level = $request->request->get('level');

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);
            $actor = $actorRepository->find($actorId);

            if ($actor) {
                $htmlFlux = $this->renderView('front/maze/actor/trick.html.twig', [
                    'actor' => $actor,
                    'minVoteCount' => $minVoteCount,
                ]);

                return new JsonResponse(['success' => true, 'message' => $htmlFlux]);
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Acteur introuvable.']);
            }
        } catch (\Exception $ex) {
            return new JsonResponse(['success' => false, 'message' => 'Impossible de charger la filmographie de l\'acteur.']);
        }
    }
}
