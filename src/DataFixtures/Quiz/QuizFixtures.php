<?php

namespace App\DataFixtures\Quiz;

use App\Entity\Quiz\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public const QUIZ_MOVIES = 'quiz-movies';
    public const QUIZ_SERIES = 'quiz-series';
    public const QUIZ_ANIMATION = 'quiz-animation';

    /**
     * {@inheritDoc}
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

        $data[] = $this->buildData('Quiz films', true, true, true, 1, '0001_main.jpg', '0001_thumbnail.jpg', self::QUIZ_MOVIES);
        $data[] = $this->buildData('Quiz séries', true, true, true, 2, '0002_main.jpg', '0002_thumbnail.jpg', self::QUIZ_SERIES);
        $data[] = $this->buildData('Quiz divers', true, true, false, 3, '0003_main.jpg', '0003_thumbnail.jpg', self::QUIZ_ANIMATION);

        return $data;
    }

    protected function buildData(
        string $name,
        bool $displayResponse,
        bool $displayTrick,
        bool $published,
        int $rank,
        string $pictureUrl,
        string $thumbnailUrl,
        string $reference

    ): Quiz {
        $data = new Quiz();
        $data->setName($name);
        $data->setDisplayResponse($displayResponse);
        $data->setDisplayTrick($displayTrick);
        $data->setPublished($published);
        $data->setRank($rank);
        $data->setPictureUrl($pictureUrl);
        $data->setThumbnailUrl($thumbnailUrl);

        $this->addReference($reference, $data);

        return $data;
    }
}
