<?php

namespace App\DataFixtures\Quiz;

use App\DataFixtures\User\UserFixtures;
use App\Entity\Quiz\Quiz;
use App\Entity\Quiz\Winner;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class WinnerFixtures extends Fixture implements DependentFixtureInterface
{
    public const WINNER_REI = 'winner-rei';
    public const WINNER_TED = 'winner-ted';
    public const WINNER_LAURA = 'winner-laura';
    public const WINNER_JULIE = 'winner-julie';

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

        $data[] = $this->buildDataWithUser(
            $this->getReference(UserFixtures::USER_REI),
            'Rei powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-10'),
            1,
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            self::WINNER_REI
        );
        $data[] = $this->buildDataWithUser(
            $this->getReference(UserFixtures::USER_TED),
            'Ted powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-11'),
            2,
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            self::WINNER_TED
        );
        $data[] = $this->buildData(
            'Laura Kawa',
            'laura.kawa@yopmail.com',
            'Laura powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-12'),
            0,
            $this->getReference(QuizFixtures::QUIZ_MOVIES),
            self::WINNER_LAURA
        );
        $data[] = $this->buildData(
            'Julie Uru',
            'julie.uru@yopmail.com',
            'Julie powaaa',
            \DateTime::createFromFormat('Y-m-d', '2018-05-13'),
            3,
            $this->getReference(QuizFixtures::QUIZ_SERIES),
            self::WINNER_JULIE
        );

        return $data;
    }

    protected function buildDataWithUser(
        User $user,
        string $comment,
        \DateTime $date,
        int $trickCount,
        Quiz $quiz,
        string $reference
    ): Winner {
        $data = $this->buildData(
            $user->getFirstname() . ' ' . $user->getLastname(),
            $user->getEmail(),
            $comment,
            $date,
            $trickCount,
            $quiz,
            $reference
        );

        $data->setUser($user);

        return $data;
    }

    protected function buildData(
        string $name,
        string $email,
        string $comment,
        \DateTime $date,
        int $trickCount,
        Quiz $quiz,
        string $reference
    ): Winner {
        $data = new Winner();
        $data->setName($name);
        $data->setEmail($email);
        $data->setComment($comment);
        $data->setDate($date);
        $data->setTrickCount($trickCount);
        $data->setQuiz($quiz);

        $this->addReference($reference, $data);

        return $data;
    }
}
