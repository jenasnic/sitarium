<?php

namespace App\DataFixtures\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Response;
use App\Tool\TextUtil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ResponseFixtures extends Fixture implements DependentFixtureInterface
{
    public const RESPONSE_MOVIE_1 = 'response-movie-1';
    public const RESPONSE_MOVIE_2 = 'response-movie-2';
    public const RESPONSE_MOVIE_3 = 'response-movie-3';
    public const RESPONSE_MOVIE_4 = 'response-movie-4';
    public const RESPONSE_MOVIE_5 = 'response-movie-5';
    public const RESPONSE_SERIE_1 = 'response-serie-1';
    public const RESPONSE_SERIE_2 = 'response-serie-2';
    public const RESPONSE_SERIE_3 = 'response-serie-3';
    public const RESPONSE_SERIE_4 = 'response-serie-4';
    public const RESPONSE_SERIE_5 = 'response-serie-5';
    public const RESPONSE_ANIMATION_1 = 'response-animation-1';
    public const RESPONSE_ANIMATION_2 = 'response-animation-2';
    public const RESPONSE_ANIMATION_3 = 'response-animation-3';
    public const RESPONSE_ANIMATION_4 = 'response-animation-4';
    public const RESPONSE_ANIMATION_5 = 'response-animation-5';

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
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            QuizFixtures::class,
        ];
    }

    /**
     * @return array
     */
    protected function getDatas(): array
    {
        $data = [];

        $quizMovies = $this->getReference(QuizFixtures::QUIZ_MOVIES);
        $quizSeries = $this->getReference(QuizFixtures::QUIZ_SERIES);
        $quizAnimation = $this->getReference(QuizFixtures::QUIZ_ANIMATION);

        $data[] = $this->buildData('Deadpool 2', 100, 100, 1, $quizMovies, self::RESPONSE_MOVIE_1);
        $data[] = $this->buildData('Rampage : Hors de contrôle', 200, 200, 1, $quizMovies, self::RESPONSE_MOVIE_2);
        $data[] = $this->buildData('Avengers : Infinity War', 300, 300, 2, $quizMovies, self::RESPONSE_MOVIE_3);
        $data[] = $this->buildData('Tomb Raider', 400, 400, 2, $quizMovies, self::RESPONSE_MOVIE_4);
        $data[] = $this->buildData('Ready Player One', 500, 500, 3, $quizMovies, self::RESPONSE_MOVIE_5);

        $data[] = $this->buildData('Breaking Bad', 100, 100, 1, $quizSeries, self::RESPONSE_SERIE_1);
        $data[] = $this->buildData('The Young Pope', 200, 200, 1, $quizSeries, self::RESPONSE_SERIE_2);
        $data[] = $this->buildData('Sherlock', 300, 300, 2, $quizSeries, self::RESPONSE_SERIE_3);
        $data[] = $this->buildData('Battlestar Galactica', 400, 400, 2, $quizSeries, self::RESPONSE_SERIE_4);
        $data[] = $this->buildData('Counterpart', 500, 500, 3, $quizSeries, self::RESPONSE_SERIE_5);

        $data[] = $this->buildData('Zombillénium', 100, 100, 1, $quizAnimation, self::RESPONSE_ANIMATION_1);
        $data[] = $this->buildData('Monstres Academy', 200, 200, 1, $quizAnimation, self::RESPONSE_ANIMATION_2);
        $data[] = $this->buildData('Le voyage d\'Arlo', 300, 300, 2, $quizAnimation, self::RESPONSE_ANIMATION_3);
        $data[] = $this->buildData('Vice-versa', 400, 400, 2, $quizAnimation, self::RESPONSE_ANIMATION_4);
        $data[] = $this->buildData('Coco', 500, 500, 3, $quizAnimation, self::RESPONSE_ANIMATION_5);

        return $data;
    }

    protected function buildData(
        string $title,
        int $positionX,
        int $positionY,
        int $size,
        Quiz $quiz,
        string $reference
    ): Response {
        $data = new Response();
        $data->setTitle($title);
        $data->setResponses(';' . strtolower($title) . ';');
        $data->setTrick(strrev(TextUtil::sanitize($title)));
        $data->setPositionX($positionX);
        $data->setPositionY($positionY);
        $data->setSize($size);
        $data->setQuiz($quiz);

        $this->addReference($reference, $data);

        return $data;
    }
}
