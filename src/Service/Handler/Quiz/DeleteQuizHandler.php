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
     * @param DeleteQuizCommand $command
     */
    public function handle(DeleteQuizCommand $command)
    {
        $pictureToDelete = $this->getParameter('kernel.root_dir') . '/../web' . $command->getQuiz()->getPictureUrl();
        $thumbnailToDelete = $this->getParameter('kernel.root_dir') . '/../web' . $command->getQuiz()->getThumbnailUrl();

        // Remove linked QuizUserResponse + Winner
        $this->userResponseRepository->removeResponsesForQuizId($command->getQuizId());
        $this->winnerRepository->removeWinnersForQuizId($command->getQuizId());

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
