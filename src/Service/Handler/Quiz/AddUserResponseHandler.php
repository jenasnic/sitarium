<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\AddUserResponseCommand;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Quiz\UserResponse;

/**
 * Allows to save new quiz response found for user.
 */
class AddUserResponseHandler
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
     * @param AddUserResponseCommand $command
     */
    public function handle(AddUserResponseCommand $command)
    {
        $userResponse = new UserResponse();
        $userResponse->setUser($command->getUser());
        $userResponse->setResponse($command->getResponse());
        $userResponse->setDate(new \DateTime());

        $this->entityManager->persist($userResponse);
        $this->entityManager->flush();
    }
}
