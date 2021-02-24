<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\ReorderQuizCommand;
use App\Repository\Quiz\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to reorder quiz (i.e. update rank property for quiz).
 */
class ReorderQuizHandler
{
    protected QuizRepository $quizRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(QuizRepository $quizRepository, EntityManagerInterface $entityManager)
    {
        $this->quizRepository = $quizRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(ReorderQuizCommand $command): void
    {
        foreach ($command->getReorderedIds() as $orderedId) {
            $quizToReorder = $this->quizRepository->find($orderedId['id']);
            $quizToReorder->setRank($orderedId['rank']);
        }

        $this->entityManager->flush();
    }
}
