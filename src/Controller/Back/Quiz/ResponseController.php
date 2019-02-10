<?php

namespace App\Controller\Back\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response as QuizResponse;
use App\Form\Quiz\ResponseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResponseController extends Controller
{
    /**
     * @Route("/admin/quiz/{quiz}/response/list", requirements={"quiz" = "\d+"}, name="bo_quiz_response_list")
     *
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function listAction(Quiz $quiz): Response
    {
        return $this->render('back/quiz/response_list.html.twig', ['quiz' => $quiz]);
    }

    /**
     * @Route("/admin/quiz/{quiz}/response/new", requirements={"quiz" = "\d+"}, name="bo_quiz_response_new")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function newAction(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        Quiz $quiz
    ): Response {
        $response = new QuizResponse();
        $response->setQuiz($quiz);
        $response->setPositionX(0);
        $response->setPositionY(0);

        return $this->addOrEditResponseAction($request, $translator, $entityManager, $response);
    }

    /**
     * @Route("/admin/quiz/{quiz}/response/edit/{response}", requirements={"quiz" = "\d+", "response" = "\d+"}, name="bo_quiz_response_edit")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param Quiz $quiz
     * @param QuizResponse $response
     *
     * @return Response
     */
    public function editAction(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        Quiz $quiz,
        QuizResponse $response
    ): Response {
        return $this->addOrEditResponseAction($request, $translator, $entityManager, $response);
    }

    /**
     * @Route("/admin/quiz/{quiz}/response/delete/{response}", requirements={"quiz" = "\d+", "response" = "\d+"}, name="bo_quiz_response_delete")
     *
     * @param EntityManagerInterface $entityManager
     * @param QuizResponse $response
     *
     * @return JsonResponse
     */
    public function deleteResponseAction(EntityManagerInterface $entityManager, QuizResponse $response): JsonResponse
    {
        try {
            $entityManager->remove($response);
            $entityManager->flush();

            return new JsonResponse(1);
        } catch (\Exception $e) {
            return new JsonResponse(0);
        }
    }

    /**
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param QuizResponse $response
     *
     * @return Response
     */
    protected function addOrEditResponseAction(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        QuizResponse $response
    ): Response {
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $entityManager->persist($response);
                    $entityManager->flush();

                    return new JsonResponse(['success' => true, 'message' => $translator->trans('back.global.save.success')]);
                } catch (\Exception $e) {
                    return new JsonResponse(['success' => false, 'message' => $translator->trans('back.global.save.error')]);
                }
            } else {
                return new JsonResponse([
                    'success' => false,
                    'message' => $translator->trans('back.global.form.errors'),
                ]);
            }
        }

        return $this->render('back/quiz/response_edit.html.twig', ['form' => $form->createView()]);
    }
}
