<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\DeleteQuizCommand;
use App\Repository\Quiz\UserResponseRepository;
use App\Repository\Quiz\WinnerRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Allows to remove quiz and all its dependencies (files...).
 */
class DeleteQuizHandler
{
    protected EntityManagerInterface $entityManager;

    protected UserResponseRepository $userResponseRepository;

    protected WinnerRepository $winnerRepository;

    protected string $rootDir;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserResponseRepository $userResponseRepository,
        WinnerRepository $winnerRepository,
        string $rootDir
    ) {
        $this->entityManager = $entityManager;
        $this->userResponseRepository = $userResponseRepository;
        $this->winnerRepository = $winnerRepository;
        $this->rootDir = $rootDir;
    }

    public function handle(DeleteQuizCommand $command): void
    {
        $pictureToDelete = $this->rootDir.'/public'.$command->getQuiz()->getPictureUrl();
        $thumbnailToDelete = $this->rootDir.'/public'.$command->getQuiz()->getThumbnailUrl();

        // Remove linked QuizUserResponse + Winner
        $this->userResponseRepository->removeResponsesForQuizId($command->getQuiz()->getId());
        $this->winnerRepository->removeWinnersForQuizId($command->getQuiz()->getId());

        $this->entityManager->remove($command->getQuiz());
        $this->entityManager->flush();

        // Delete quiz picture file if exist
        if (file_exists($pictureToDelete) && is_file($pictureToDelete)) {
            unlink($pictureToDelete);
        }
        if (file_exists($thumbnailToDelete) && is_file($thumbnailToDelete)) {
            unlink($thumbnailToDelete);
        }
    }
}
