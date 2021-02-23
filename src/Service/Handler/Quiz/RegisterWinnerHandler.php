<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\RegisterWinnerCommand;
use App\Entity\Quiz\Winner;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to save new winner for quiz.
 */
class RegisterWinnerHandler
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(RegisterWinnerCommand $command): void
    {
        $winner = new Winner();
        $winner->setQuiz($command->getQuiz());
        $winner->setUser($command->getUser());
        $winner->setDate(new \DateTime());

        $this->entityManager->persist($winner);
        $this->entityManager->flush();
    }
}
