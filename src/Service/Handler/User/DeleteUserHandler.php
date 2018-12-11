<?php

namespace App\Service\Handler\User;

use App\Domain\Command\User\DeleteUserCommand;
use App\Repository\Quiz\UserResponseRepository;
use App\Repository\Quiz\WinnerRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to remove user and its dependencies in current database.
 */
class DeleteUserHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var UserResponseRepository
     */
    protected $userResponseRepository;

    /**
     * @var WinnerRepository
     */
    protected $winnerRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserResponseRepository $userResponseRepository
     * @param WinnerRepository $winnerRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserResponseRepository $userResponseRepository,
        WinnerRepository $winnerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userResponseRepository = $userResponseRepository;
        $this->winnerRepository = $winnerRepository;
    }

    /**
     * @param DeleteUserCommand $command
     */
    public function handle(DeleteUserCommand $command)
    {
        // Remove response linked to user + remove user link for winner
        $this->userResponseRepository->removeResponsesForUserId($command->getUser()->getId());
        $this->winnerRepository->removeWinnersForUserId($command->getUser()->getId());

        $this->entityManager->remove($command->getUser());
        $this->entityManager->flush();
    }
}
