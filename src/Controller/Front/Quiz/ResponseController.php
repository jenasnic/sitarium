<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\AddUserResponseCommand;
use App\Domain\Command\Quiz\ClearUserResponseCommand;
use App\Entity\Quiz\Quiz;
use App\Repository\Quiz\ResponseRepository;
use App\Service\Handler\Quiz\AddUserResponseHandler;
use App\Service\Handler\Quiz\ClearUserResponseHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends Controller
{
    /**
     * @Route("/quiz/ajax/valider-reponse", name="fo_quiz_check_response")
     *
     * @param Request $request
     * @param ResponseRepository $responseRepository
     * @param AddUserResponseHandler $handler
     *
     * @return JsonResponse
     */
    public function checkResponseAction(
        Request $request,
        ResponseRepository $responseRepository,
        AddUserResponseHandler $handler
    ): JsonResponse {
        $quizId = $request->request->get('quizId');
        $response = trim($request->request->get('response'));

        try {
            if ($quizId > 0 && strlen($response) > 0) {
                $responseFound = $responseRepository->searchMatchingResponseForQuizId($response, $quizId);

                if ($responseFound) {
                    // If logged user => save response for current user
                    if ($this->getUser()) {
                        $handler->handle(new AddUserResponseCommand($this->getUser(), $responseFound));
                    }

                    return new JsonResponse([
                        'success' => true,
                        'title' => $responseFound->getTitle(),
                        'id' => $responseFound->getId(),
                        'positionX' => $responseFound->getPositionX(),
                        'positionY' => $responseFound->getPositionY(),
                        'size' => $responseFound->getSize()
                    ]);
                }
            }

            return new JsonResponse(['success' => false, 'message' => 'Réponse incorrecte.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors du traitement de la réponse.']);
        }
    }

    /**
     * @Route("/quiz/ajax/indice", name="fo_quiz_trick", methods="POST")
     *
     * @param Request $request
     * @param ResponseRepository $responseRepository
     *
     * @return JsonResponse
     */
    public function quizTrick(Request $request, ResponseRepository $responseRepository): JsonResponse
    {
        $quizId = $request->request->get('quizId');
        $positionX = $request->request->get('positionX');
        $positionY = $request->request->get('positionY');

        try {
            $responses = $responseRepository->getResponsesWithCoordonates($positionX, $positionY, $quizId);

            if ($responses) {
                $responses = array_map(function($response) {return $response->getTrick();}, $responses);
                return new JsonResponse(['success' => true, 'trick' => $responses]);
            }
            else
                return new JsonResponse(['success' => false, 'message' => 'Aucun indice trouvé pour la zone indiquée.']);
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la récupération des indices.']);
        }
    }

    /**
     * @Route("/quiz/ajax/recommencer/{quiz}", requirements={"quiz" = "\d+"}, name="fo_quiz_clear_user_response")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param ClearUserResponseHandler $handler
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function clearUserResponseAction(ClearUserResponseHandler $handler, Quiz $quiz): JsonResponse
    {
        try {
            $handler->handle(new ClearUserResponseCommand($this->getUser(), $quiz));

            return new JsonResponse(array('success' => true));
        }
        catch (\Exception $e) {
            return new JsonResponse(array('success' => false));
        }

        return new JsonResponse(array('success' => true));
    }
}
