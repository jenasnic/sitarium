<?php

namespace App\Controller\Back\Quiz;

use App\Domain\Command\Quiz\DeleteQuizCommand;
use App\Domain\Command\Quiz\ReorderQuizCommand;
use App\Domain\Command\Quiz\SaveQuizCommand;
use App\Entity\Quiz\Quiz;
use App\Enum\Quiz\SessionValues;
use App\Form\Quiz\QuizType;
use App\Repository\Quiz\QuizRepository;
use App\Service\Handler\Quiz\DeleteQuizHandler;
use App\Service\Handler\Quiz\ReorderQuizHandler;
use App\Service\Handler\Quiz\SaveQuizHandler;
use App\Service\Quiz\TmdbLinkBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    /**
     * @Route("/admin/quiz/list", name="bo_quiz_list", methods="GET")
     *
     * @param QuizRepository $quizRepository
     *
     * @return Response
     */
    public function listAction(QuizRepository $quizRepository): Response
    {
        return $this->render('back/quiz/list.html.twig', ['quizs' => $quizRepository->findBy([], ['rank' => 'asc'])]);
    }

    /**
     * @Route("/admin/quiz/reorder", name="bo_quiz_reorder", methods="POST")
     *
     * @param Request $request
     * @param ReorderQuizHandler $handler
     *
     * @return JsonResponse
     */
    public function reorderAction(Request $request, ReorderQuizHandler $handler): JsonResponse
    {
        try {
            $handler->handle(new ReorderQuizCommand(json_decode($request->getContent())));

            return new JsonResponse(['success' => true, 'message' => 'Réordonnancement des quiz OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors du réordonnancement des quiz']);
        }
    }

    /**
     * @Route("/admin/quiz/new", name="bo_quiz_new", methods="POST")
     *
     * @param Request $request
     * @param QuizRepository $quizRepository
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function newAction(
        Request $request,
        QuizRepository $quizRepository,
        EntityManagerInterface $entityManager
    ): Response {
        try {
            $quizToCreate = new Quiz();
            $quizToCreate->setName($request->request->get('name'));
            $quizToCreate->setRank($quizRepository->getMaxRank() + 1);

            $entityManager->persist($quizToCreate);
            $entityManager->flush();

            return $this->redirectToRoute('bo_quiz_edit', ['quiz' => $quizToCreate->getId()]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la création');

            return $this->redirectToRoute('bo_quiz_list');
        }
    }

    /**
     * @Route("/admin/quiz/edit/{quiz}", requirements={"quiz" = "\d+"}, name="bo_quiz_edit")
     *
     * @param Request $request
     * @param Quiz $quiz
     * @param SaveQuizHandler $handler
     *
     * @return Response
     */
    public function editAction(Request $request, Quiz $quiz, SaveQuizHandler $handler): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle(new SaveQuizCommand($quiz));
                $this->addFlash('info', 'Sauvegarde OK');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la sauvegarde');
            }

            return $this->redirectToRoute('bo_quiz_list');
        }

        return $this->render('back/quiz/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/quiz/{quiz}/delete", requirements={"quiz" = "\d+"}, name="bo_quiz_delete")
     *
     * @param DeleteQuizHandler $handler
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function deleteAction(DeleteQuizHandler $handler, Quiz $quiz): Response
    {
        try {
            $handler->handle(new DeleteQuizCommand($quiz));
            $this->addFlash('info', 'Suppression OK');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la suppression');
        }

        return $this->redirectToRoute('bo_quiz_list');
    }

    /**
     * @Route("/admin/quiz/{quiz}/tmdb-link", requirements={"quiz" = "\d+"}, name="bo_quiz_link_tmdb_start")
     *
     * @param TmdbLinkBuilder $tmdbLinkBuilder
     * @param Quiz $quiz
     *
     * @return JsonResponse
     */
    public function tmdbLinkAction(TmdbLinkBuilder $tmdbLinkBuilder, Quiz $quiz): JsonResponse
    {
        try {
            $tmdbLinkBuilder->build($quiz->getId());

            return new JsonResponse(['success' => true, 'message' => 'Création des liens TMDB OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de la création des liens TMDB']);
        }
    }

    /**
     * @Route("/admin/quiz/tmdb-link/step", name="bo_quiz_link_tmdb_progress")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tmdbLinkStepAction(Request $request): JsonResponse
    {
        return new JsonResponse([
            'current' => $request->getSession()->get(SessionValues::SESSION_BUILD_TMDB_LINK_PROGRESS),
            'total' => $request->getSession()->get(SessionValues::SESSION_BUILD_TMDB_LINK_TOTAL),
        ]);
    }

    /**
     * @Route("/admin/quiz/set-picture-size", name="bo_quiz_set_picture_size", methods="GET")
     *
     * @param QuizRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param string $rootDir
     *
     * @return Response
     */
    public function setPictureSizeAction(QuizRepository $repository, EntityManagerInterface $entityManager, string $rootDir): Response
    {
        $quizList = $repository->findAll();

        /* @var Quiz $quiz */
        foreach ($quizList as $quiz) {
            $imageSize = getimagesize(sprintf('%s/public%s', $rootDir, $quiz->getPictureUrl()));
            $quiz->setPictureWidth($imageSize[0]);
            $quiz->setPictureHeight($imageSize[1]);

            $entityManager->persist($quiz);
        }
        $entityManager->flush();

        return new JsonResponse('Mise à jour de la taille des images pour tous les quiz !');
    }
}
