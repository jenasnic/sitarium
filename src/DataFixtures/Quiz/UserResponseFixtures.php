<?php

namespace App\DataFixtures\Quiz;

use App\DataFixtures\UserFixtures;
use App\Entity\Quiz\Response;
use App\Entity\Quiz\UserResponse;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserResponseFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
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
            ResponseFixtures::class,
        ];
    }

    /**
     * @return array<UserResponse>
     */
    protected function getDatas(): array
    {
        $data = [];

        $userRay = $this->getReference(UserFixtures::USER_RAY);
        $userTed = $this->getReference(UserFixtures::USER_TED);
        $userJeannot = $this->getReference(UserFixtures::USER_JEANNOT);

        $responseMovie1 = $this->getReference(ResponseFixtures::RESPONSE_MOVIE_1);
        $responseMovie2 = $this->getReference(ResponseFixtures::RESPONSE_MOVIE_2);
        $responseMovie3 = $this->getReference(ResponseFixtures::RESPONSE_MOVIE_3);
        $responseMovie4 = $this->getReference(ResponseFixtures::RESPONSE_MOVIE_4);
        $responseMovie5 = $this->getReference(ResponseFixtures::RESPONSE_MOVIE_5);
        $responseSerie1 = $this->getReference(ResponseFixtures::RESPONSE_SERIE_1);
        $responseSerie2 = $this->getReference(ResponseFixtures::RESPONSE_SERIE_2);
        $responseSerie3 = $this->getReference(ResponseFixtures::RESPONSE_SERIE_3);
        $responseAnimation1 = $this->getReference(ResponseFixtures::RESPONSE_ANIMATION_1);
        $responseAnimation2 = $this->getReference(ResponseFixtures::RESPONSE_ANIMATION_2);

        $data[] = $this->buildData($userRay, $responseMovie1, DateTime::createFromFormat('Y-m-d', '2018-05-09'));
        $data[] = $this->buildData($userRay, $responseMovie2, DateTime::createFromFormat('Y-m-d', '2018-05-09'));
        $data[] = $this->buildData($userRay, $responseMovie3, DateTime::createFromFormat('Y-m-d', '2018-05-09'));
        $data[] = $this->buildData($userRay, $responseMovie4, DateTime::createFromFormat('Y-m-d', '2018-05-10'));
        $data[] = $this->buildData($userRay, $responseMovie5, DateTime::createFromFormat('Y-m-d', '2018-05-10'));
        $data[] = $this->buildData($userRay, $responseSerie1, DateTime::createFromFormat('Y-m-d', '2018-05-11'));
        $data[] = $this->buildData($userRay, $responseSerie2, DateTime::createFromFormat('Y-m-d', '2018-05-11'));
        $data[] = $this->buildData($userRay, $responseSerie3, DateTime::createFromFormat('Y-m-d', '2018-05-12'));
        $data[] = $this->buildData($userRay, $responseAnimation1, DateTime::createFromFormat('Y-m-d', '2018-05-12'));
        $data[] = $this->buildData($userRay, $responseAnimation2, DateTime::createFromFormat('Y-m-d', '2018-05-12'));
        $data[] = $this->buildData($userTed, $responseMovie1, DateTime::createFromFormat('Y-m-d', '2018-05-09'));
        $data[] = $this->buildData($userTed, $responseMovie2, DateTime::createFromFormat('Y-m-d', '2018-05-10'));
        $data[] = $this->buildData($userTed, $responseMovie3, DateTime::createFromFormat('Y-m-d', '2018-05-10'));
        $data[] = $this->buildData($userTed, $responseMovie4, DateTime::createFromFormat('Y-m-d', '2018-05-10'));
        $data[] = $this->buildData($userTed, $responseMovie5, DateTime::createFromFormat('Y-m-d', '2018-05-11'));
        $data[] = $this->buildData($userTed, $responseSerie1, DateTime::createFromFormat('Y-m-d', '2018-05-13'));
        $data[] = $this->buildData($userTed, $responseSerie2, DateTime::createFromFormat('Y-m-d', '2018-05-13'));
        $data[] = $this->buildData($userTed, $responseAnimation1, DateTime::createFromFormat('Y-m-d', '2018-05-12'));
        $data[] = $this->buildData($userJeannot, $responseMovie1, DateTime::createFromFormat('Y-m-d', '2018-05-11'));
        $data[] = $this->buildData($userJeannot, $responseMovie2, DateTime::createFromFormat('Y-m-d', '2018-05-11'));
        $data[] = $this->buildData($userJeannot, $responseMovie3, DateTime::createFromFormat('Y-m-d', '2018-05-12'));
        $data[] = $this->buildData($userJeannot, $responseAnimation1, DateTime::createFromFormat('Y-m-d', '2018-05-12'));

        return $data;
    }

    protected function buildData(
        User $user,
        Response $response,
        DateTime $date
    ): UserResponse {
        $data = new UserResponse();
        $data->setUser($user);
        $data->setResponse($response);
        $data->setDate($date);

        return $data;
    }
}
