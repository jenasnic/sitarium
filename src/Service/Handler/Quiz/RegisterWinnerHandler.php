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
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param RegisterWinnerCommand $command
     */
    public function handle(RegisterWinnerCommand $command)
    {
        $winner = new Winner();
        $winner->setQuiz($command->getQuiz());
        $winner->setUser($command->getUser());
        $winner->setTrickCount($command->getTrickCount());
        // NOTE : for comment => replace break lines with HTML tag
        $winner->setComment(str_replace("\n", '<br/>', $command->getComment()));

        $this->entityManager->persist($winner);
        $this->entityManager->flush();
    }
}
