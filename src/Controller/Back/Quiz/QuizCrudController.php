<?php

namespace App\Controller\Back\Quiz;

use App\Domain\Command\Quiz\DeleteQuizCommand;
use App\Domain\Command\Quiz\SaveQuizCommand;
use App\Entity\Quiz\Quiz;
use App\Form\Quiz\QuizType;
use App\Repository\Quiz\QuizRepository;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Handler\Quiz\DeleteQuizHandler;
use App\Service\Handler\Quiz\SaveQuizHandler;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class QuizCrudController extends AbstractController
{
    /**
     * @Route("/admin/quiz/new", name="bo_quiz_new", methods="POST")
     */
    public function newAction(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        QuizRepository $quizRepository
    ): Response {
        try {
            $quizToCreate = new Quiz();
            $quizToCreate->setName($request->request->get('name'));
            $quizToCreate->setRank($quizRepository->getMaxRank() + 1);

            $entityManager->persist($quizToCreate);
            $entityManager->flush();

            return $this->redirectToRoute('bo_quiz_edit', ['quiz' => $quizToCreate->getId()]);
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.add.error'));

            return $this->redirectToRoute('bo_quiz_list');
        }
    }

    /**
     * @Route("/admin/quiz/edit/{quiz}", requirements={"quiz": "\d+"}, name="bo_quiz_edit")
     */
    public function editAction(
        Request $request,
        BuildProcessRepository $buildProcessRepository,
        TranslatorInterface $translator,
        SaveQuizHandler $handler,
        Quiz $quiz
    ): Response {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle(new SaveQuizCommand($quiz));
                $this->addFlash('info', $translator->trans('back.global.save.success'));

                return $this->redirectToRoute('bo_quiz_list');
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('back.global.save.error'));
            }
        }

        return $this->render('back/quiz/edit.html.twig', [
            'form' => $form->createView(),
            'pendingProcess' => $buildProcessRepository->findPendingProcess(),
        ]);
    }

    /**
     * @Route("/admin/quiz/delete/{quiz}", requirements={"quiz": "\d+"}, name="bo_quiz_delete")
     */
    public function deleteAction(
        TranslatorInterface $translator,
        DeleteQuizHandler $handler,
        Quiz $quiz
    ): Response {
        try {
            $handler->handle(new DeleteQuizCommand($quiz));
            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_quiz_list');
    }
}
