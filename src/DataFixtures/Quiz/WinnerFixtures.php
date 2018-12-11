<?php

namespace App\DataFixtures\Quiz;

use App\DataFixtures\UserFixtures;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Winner;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

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
            $this->getReference(UserFixtures::USER_REI),
            'Rei powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-10'),
            1,
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            self::WINNER_REI
        );
        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_TED),
            'Ted powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-11'),
            2,
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            self::WINNER_TED
        );
        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_JEANNOT),
            'Jeannot powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-12'),
            3,
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            self::WINNER_JEANNOT
        );
        $data[] = $this->buildData(
            $this->getReference(UserFixtures::USER_DAN),
            'Dan powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-13'),
            3,
            $this->getReference(QuizFixtures::QUIZ_SERIES),
            self::WINNER_DAN
        );

        return $data;
    }

    /**
     * @param User $user
     * @param string $comment
     * @param \DateTime $date
     * @param int $trickCount
     * @param Quiz $quiz
     * @param string $reference
     *
     * @return Winner
     */
    protected function buildData(
        User $user,
        string $comment,
        \DateTime $date,
        int $trickCount,
        Quiz $quiz,
        string $reference
    ): Winner {
        $data = new Winner();
        $data->setUser($user);
        $data->setComment($comment);
        $data->setDate($date);
        $data->setTrickCount($trickCount);
        $data->setQuiz($quiz);

        $this->addReference($reference, $data);

        return $data;
    }
}
