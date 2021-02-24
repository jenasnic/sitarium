<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response as QuizResponse;
use App\Repository\Quiz\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocateController extends AbstractController
{
    /**
     * @Route("/admin/quiz/{quiz}/response/locate", requirements={"quiz" = "\d+"}, name="bo_quiz_response_locate")
     */
    public function indexAction(Quiz $quiz): Response
    {
        return $this->render('back/quiz/response_locate.html.twig', ['quiz' => $quiz]);
    }

    /**
     * @Route("/admin/quiz/response/set-location", name="bo_quiz_locate_response", methods="POST")
     */
    public function setLocationAction(Request $request, ResponseRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $response = $repository->find(intval($data['responseId']));

            $response->setPositionX(floatval($data['positionX']));
            $response->setPositionY(floatval($data['positionY']));
            $response->setWidth(floatval($data['width']));
            $response->setHeight(floatval($data['height']));

            $entityManager->persist($response);
            $entityManager->flush();

            return new JsonResponse(1);
        } catch (Exception $e) {
            return new JsonResponse(0);
        }
    }

    /**
     * @Route("/admin/quiz/response/get-location", name="bo_quiz_response_location", methods="GET")
     */
    public function getLocationAction(
        Request $request,
        TranslatorInterface $translator,
        ResponseRepository $repository
    ): Response {
        try {
            /* @var QuizResponse $response */
            $response = $repository->find($request->query->get('id'));

            if (
                null === $response
                || null === $response->getPositionX()
                || null === $response->getPositionY()
                || null === $response->getWidth()
                || null === $response->getHeight()
            ) {
                return new JsonResponse(['success' => false, 'message' => $translator->trans('back.quiz.location.not_found')]);
            }

            $location = [
                'positionX' => $response->getPositionX(),
                'positionY' => $response->getPositionY(),
                'width' => $response->getWidth(),
                'height' => $response->getHeight(),
            ];

            return new JsonResponse(['success' => true, 'info' => $location]);
        } catch (Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $translator->trans('back.quiz.location.error')]);
        }
    }
}
