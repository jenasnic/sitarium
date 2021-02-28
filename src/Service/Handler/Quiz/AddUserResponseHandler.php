<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\AddUserResponseCommand;
use App\Entity\Quiz\UserResponse;
use App\Repository\Quiz\UserResponseRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to save new quiz response found for user.
 */
class AddUserResponseHandler
{
    protected EntityManagerInterface $entityManager;

    protected UserResponseRepository $userResponseRepository;

    public function __construct(EntityManagerInterface $entityManager, UserResponseRepository $userResponseRepository)
    {
        $this->entityManager = $entityManager;
        $this->userResponseRepository = $userResponseRepository;
    }

    public function handle(AddUserResponseCommand $command): void
    {
        $responseAlreadyFound = $this->userResponseRepository->checkExistingResponseForUserId(
            $command->getUser()->getId(),
            $command->getResponse()->getId()
        );

        if (!$responseAlreadyFound) {
            $userResponse = new UserResponse();
            $userResponse->setUser($command->getUser());
            $userResponse->setResponse($command->getResponse());
            $userResponse->setDate(new DateTime());

            $this->entityManager->persist($userResponse);
            $this->entityManager->flush();
        }
    }
}
