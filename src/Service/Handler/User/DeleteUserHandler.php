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
    protected EntityManagerInterface $entityManager;

    protected UserResponseRepository $userResponseRepository;

    protected WinnerRepository $winnerRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserResponseRepository $userResponseRepository,
        WinnerRepository $winnerRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userResponseRepository = $userResponseRepository;
        $this->winnerRepository = $winnerRepository;
    }

    public function handle(DeleteUserCommand $command): void
    {
        // Remove response linked to user + remove user link for winner
        $this->userResponseRepository->removeResponsesForUserId($command->getUser()->getId());
        $this->winnerRepository->removeWinnersForUserId($command->getUser()->getId());

        $this->entityManager->remove($command->getUser());
        $this->entityManager->flush();
    }
}
