<?php

namespace App\Controller\Back\Quiz;

use App\Domain\Command\Quiz\DeleteQuizCommand;
use App\Domain\Command\Quiz\SaveQuizCommand;
use App\Entity\Quiz\Quiz;
use App\Form\Quiz\QuizType;
use App\Repository\Quiz\QuizRepository;
use App\Service\Handler\Quiz\DeleteQuizHandler;
use App\Service\Handler\Quiz\SaveQuizHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizCrudController extends Controller
{
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

                return $this->redirectToRoute('bo_quiz_list');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la sauvegarde');
            }
        }

        return $this->render('back/quiz/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/quiz/delete/{quiz}", requirements={"quiz" = "\d+"}, name="bo_quiz_delete")
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
}
