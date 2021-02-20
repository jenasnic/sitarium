<?php

namespace App\DataFixtures\Quiz;

use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public const QUIZ_MOVIES = 'quiz-movies';
    public const QUIZ_SERIES = 'quiz-series';
    public const QUIZ_ANIMATION = 'quiz-animation';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $datas = $this->getDatas();

        foreach ($datas as $data) {
            $manager->persist($data);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    protected function getDatas(): array
    {
        $data = [];

        $data[] = $this->buildData('Quiz films', 'film', true, true, true, 1, '0001_main.jpg', 2500, 1600, '0001_thumbnail.jpg', self::QUIZ_MOVIES);
        $data[] = $this->buildData('Quiz séries', 'series', true, true, true, 2, '0002_main.jpg', 1500, 2200, '0002_thumbnail.jpg', self::QUIZ_SERIES);
        $data[] = $this->buildData('Quiz divers', 'divers', true, true, false, 3, '0003_main.jpg', 1900, 1900, '0003_thumbnail.jpg', self::QUIZ_ANIMATION);

        return $data;
    }

    /**
     * @param string $name
     * @param string $slug
     * @param bool $displayResponse
     * @param bool $displayTrick
     * @param bool $published
     * @param int $rank
     * @param string $pictureUrl
     * @param int $pictureWidth
     * @param int $pictureHeight
     * @param string $thumbnailUrl
     * @param string $reference
     *
     * @return Quiz
     */
    protected function buildData(
        string $name,
        string $slug,
        bool $displayResponse,
        bool $displayTrick,
        bool $published,
        int $rank,
        string $pictureUrl,
        int $pictureWidth,
        int $pictureHeight,
        string $thumbnailUrl,
        string $reference
    ): Quiz {
        $data = new Quiz();
        $data->setName($name);
        $data->setSlug($slug);
        $data->setDisplayResponse($displayResponse);
        $data->setDisplayTrick($displayTrick);
        $data->setPublished($published);
        $data->setRank($rank);
        $data->setPictureUrl($pictureUrl);
        $data->setPictureWidth($pictureWidth);
        $data->setPictureHeight($pictureHeight);
        $data->setThumbnailUrl($thumbnailUrl);

        $this->addReference($reference, $data);

        return $data;
    }
}
