<?php

namespace App\Controller\Front\Quiz;

use App\Domain\Command\Quiz\RegisterWinnerCommand;
use App\Entity\Quiz\Quiz;
use App\Service\Handler\Quiz\RegisterWinnerHandler;
use App\Service\Quiz\ResolveQuizValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WinnerController extends Controller
{
    /**
     * @Route("/quiz/ajax/enregistrer-vainqueur/{quiz}", requirements={"quiz" = "\d+"}, name="fo_quiz_register_winner", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param ResolveQuizValidator $validator
     * @param RegisterWinnerHandler $handler
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function registerWinnerAction(
        Request $request,
        ResolveQuizValidator $validator,
        RegisterWinnerHandler $handler,
        Quiz $quiz
    ): JsonResponse {
        try {
            // Get all responses found and check if quiz successfully completed
            $responses = $request->request->get('responses');
            $responses = explode(';', $responses);

            if ($validator->validateWinner($responses, $quiz)) {
                $command = new RegisterWinnerCommand(
                    $quiz,
                    $this->getUser(),
                    $request->request->get('comment'),
                    $request->request->get('trickCount', 0)
                );

                $handler->handle($command);

                return new JsonResponse(['success' => true, 'message' => 'Vous avez bien été enregistré !']);
            }
            else {
                return new JsonResponse(['success' => false, 'message' => 'Vous n\'avez pas résolu le quiz !']);
            }
        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de votre enregistrement']);
        }
    }
}
