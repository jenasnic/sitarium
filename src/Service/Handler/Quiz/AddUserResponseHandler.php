<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\AddUserResponseCommand;
use App\Repository\Quiz\UserResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Quiz\UserResponse;

/**
 * Allows to save new quiz response found for user.
 */
class AddUserResponseHandler
{
    /**
     * @var UserResponseRepository
     */
    protected $userResponseRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param UserResponseRepository $userResponseRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserResponseRepository $userResponseRepository, EntityManagerInterface $entityManager)
    {
        $this->userResponseRepository = $userResponseRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddUserResponseCommand $command
     */
    public function handle(AddUserResponseCommand $command)
    {
        $responseAlreadyFound = $this->userResponseRepository->checkExistingResponseForUserId(
            $command->getUser->getId(),
            $command->getResponse()->getId()
        );

        if (!$responseAlreadyFound) {
            $userResponse = new UserResponse();
            $userResponse->setUser($command->getUser());
            $userResponse->setResponse($command->getResponse());
            $userResponse->setDate(new \DateTime());

            $this->entityManager->persist($userResponse);
            $this->entityManager->flush();
        }
    }
}
