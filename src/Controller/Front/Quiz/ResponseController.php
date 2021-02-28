<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\AddUserResponseCommand;
use App\Repository\Quiz\ResponseRepository;
use App\Service\Handler\Quiz\AddUserResponseHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResponseController extends AbstractController
{
    /**
     * @Route("/quiz/ajax/valider-reponse", name="fo_quiz_check_response", methods="POST")
     */
    public function checkResponseAction(
        Request $request,
        TranslatorInterface $translator,
        ResponseRepository $responseRepository,
        AddUserResponseHandler $handler
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $quizId = $data['quizId'];
        $response = trim($data['response']);

        try {
            if ($quizId > 0 && strlen($response) > 0) {
                $responseFound = $responseRepository->searchMatchingResponseForQuizId($response, $quizId);

                if ($responseFound) {
                    // If logged user => save response for current user (if not already done)
                    if ($this->getUser()) {
                        $handler->handle(new AddUserResponseCommand($this->getUser(), $responseFound));
                    }

                    return new JsonResponse([
                        'success' => true,
                        'title' => $responseFound->getTitle(),
                        'id' => $responseFound->getId(),
                        'positionX' => $responseFound->getPositionX(),
                        'positionY' => $responseFound->getPositionY(),
                        'width' => $responseFound->getWidth(),
                        'height' => $responseFound->getHeight(),
                    ]);
                }
            }

            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.quiz.response.incorrect')]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.quiz.response.process_error')]);
        }
    }

    /**
     * @Route("/quiz/ajax/indice", name="fo_quiz_trick", methods="POST")
     */
    public function quizTrick(Request $request, TranslatorInterface $translator, ResponseRepository $responseRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $quizId = $data['quizId'];
        $positionX = $data['positionX'];
        $positionY = $data['positionY'];

        try {
            $responses = $responseRepository->getResponsesWithCoordonates($positionX, $positionY, $quizId);

            if ($responses) {
                $responses = array_map(function ($response) {
                    return $response->getTrick();
                }, $responses);

                return new JsonResponse(['success' => true, 'trick' => $responses]);
            }

            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.quiz.trick.no_trick_on_location')]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('front.quiz.trick.process_error')]);
        }
    }
}
