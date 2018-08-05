<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\SaveQuizCommand;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Quiz\Quiz;

/**
 * Allows to save quiz when editing => process uploaded files.
 */
class SaveQuizHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string $rootDir
     */
    public function __construct(EntityManagerInterface $entityManager, string $rootDir)
    {
        $this->entityManager = $entityManager;
        $this->rootDir = $rootDir;
    }

    /**
     * @param SaveQuizCommand $command
     */
    public function handle(SaveQuizCommand $command)
    {
        // Process upload
        $this->processUpload($command->getQuiz());

        $this->entityManager->persist($command->getQuiz());
        $this->entityManager->flush();
    }

    /**
     * Allows to upload file defined in Quiz object.
     *
     * @param Quiz $quiz quiz we want to upload picture
     */
    private function processUpload(Quiz $quiz)
    {
        $relativeFolderPath = '/userfiles/quiz';
        $absoluteFolderPath = $this->rootDir . '/public' . $relativeFolderPath;
        $prefix = sprintf('%04d', $quiz->getId());

        // If picture is defined => process upload
        if (null !== $quiz->getPictureFile()) {

            // Remove old picture if exist
            $oldPictureToDelete = $this->rootDir . '/public' . $quiz->getPictureUrl();
            if (file_exists($oldPictureToDelete) && is_file($oldPictureToDelete)) {
                unlink($oldPictureToDelete);
            }

            $pictureName = $prefix . '_main.' . $quiz->getPictureFile()-> getClientOriginalExtension();

            // Move file in userfiles folder + save new URL
            $quiz->getPictureFile()->move($absoluteFolderPath, $pictureName);
            $quiz->setPictureUrl($relativeFolderPath . '/' . $pictureName);
        }

        // If thumbnail is defined => process upload
        if (null !== $quiz->getThumbnailFile()) {

            // Remove old thumbnail if exist
            $oldThumbnailToDelete = $this->rootDir . '/public' . $quiz->getThumbnailUrl();
            if (file_exists($oldThumbnailToDelete) && is_file($oldThumbnailToDelete)) {
                unlink($oldThumbnailToDelete);
            }

            $thumbnailName = $prefix . '_thumbnail.' . $quiz->getThumbnailFile()-> getClientOriginalExtension();

            // Move file in userfiles folder + save new URL
            $quiz->getThumbnailFile()->move($absoluteFolderPath, $thumbnailName);
            $quiz->setThumbnailUrl($relativeFolderPath . '/' . $thumbnailName);
        }
    }
}
