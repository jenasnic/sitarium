<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\ClearUserResponseCommand;
use App\Repository\Quiz\UserResponseRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to clear all responses found for specified user and quiz.
 */
class ClearUserResponseHandler
{
    protected UserResponseRepository $userResponseRepository;

    protected EntityManagerInterface $entityManager;

    public function __construct(UserResponseRepository $userResponseRepository, EntityManagerInterface $entityManager)
    {
        $this->userResponseRepository = $userResponseRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(ClearUserResponseCommand $command): void
    {
        $responses = $this->userResponseRepository->getResponsesForUserIdAndQuizId(
            $command->getUser()->getId(),
            $command->getQuiz()->getId()
        );

        // NOTE : Doctine doesn't allow to remove object using join in request => get all object and remove them one by one...
        foreach ($responses as $response) {
            $this->entityManager->remove($response);
        }

        $this->entityManager->flush();
    }
}
