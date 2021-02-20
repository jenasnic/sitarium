<?php

namespace App\DataFixtures\Quiz;

use App\DataFixtures\UserFixtures;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Winner;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WinnerFixtures extends Fixture implements DependentFixtureInterface
{
    public const WINNER_REI = 'winner-rei';
    public const WINNER_TED = 'winner-ted';
    public const WINNER_JEANNOT = 'winner-jeannot';
    public const WINNER_DAN = 'winner-dan';

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
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            QuizFixtures::class,
        ];
    }

    /**
     * @return array
     */
    protected function getDatas(): array
    {
        $data = [];

        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_RAY),
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            \DateTime::createFromFormat('Y-m-d', '2018-05-10'),
            self::WINNER_REI
        );
        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_TED),
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            \DateTime::createFromFormat('Y-m-d', '2018-05-11'),
            self::WINNER_TED
        );
        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_JEANNOT),
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            \DateTime::createFromFormat('Y-m-d', '2018-05-12'),
            self::WINNER_JEANNOT
        );
        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_DAN),
            $this->getReference(QuizFixtures::QUIZ_SERIES),
            \DateTime::createFromFormat('Y-m-d', '2018-05-13'),
            self::WINNER_DAN
        );

        return $data;
    }

    /**
     * @param User $user
     * @param Quiz $quiz
     * @param \DateTime $date
     * @param string $reference
     *
     * @return Winner
     */
    protected function buildData(
        User $user,
        Quiz $quiz,
        \DateTime $date,
        string $reference
    ): Winner {
        $data = new Winner();
        $data->setUser($user);
        $data->setDate($date);
        $data->setQuiz($quiz);

        $this->addReference($reference, $data);

        return $data;
    }
}
