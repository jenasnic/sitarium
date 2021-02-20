<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Tool\PasswordUtil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = 'user-admin';
    public const USER_RAY = 'user-ray';
    public const USER_TED = 'user-ted';
    public const USER_JEANNOT = 'user-jeannot';
    public const USER_JIM = 'user-jim';
    public const USER_DAN = 'user-dan';

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

        $data[] = $this->buildData('JC', '', 'jc@jc.com', 'admin', 'admin', ['ROLE_ADMIN', 'ROLE_USER'], self::USER_ADMIN);
        $data[] = $this->buildData('Ray', 'Ichido', 'ray.ichido@yopmail.com', 'ray', 'pwd', ['ROLE_USER'], self::USER_RAY);
        $data[] = $this->buildData('Ted', 'Reietsu', 'ted.reietsu@yopmail.com', 'ted', 'pwd', ['ROLE_USER'], self::USER_TED);
        $data[] = $this->buildData('Jeannot', 'Schusse', 'jeannot.schusse@yopmail.com', 'jeannot', 'pwd', ['ROLE_USER'], self::USER_JEANNOT);
        $data[] = $this->buildData('Jim', 'Daima', 'jim.daima@yopmail.com', 'jim', 'pwd', ['ROLE_USER'], self::USER_JIM);
        $data[] = $this->buildData('Dan', 'Monohoshi', 'dan.monohoshi@yopmail.com', 'dan', 'pwd', ['ROLE_USER'], self::USER_DAN);

        return $data;
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $username
     * @param string $password
     * @param array $roles
     * @param string $reference
     *
     * @return User
     */
    protected function buildData(
        string $firstname,
        string $lastname,
        string $email,
        string $username,
        string $password,
        array $roles,
        string $reference
    ): User {
        $data = new User();
        $data->setFirstname($firstname);
        $data->setLastname($lastname);
        $data->setEmail($email);
        $data->setUsername($username);
        $data->setPassword(PasswordUtil::encodePassword($password));

        foreach ($roles as $role) {
            $data->addRole($role);
        }

        $this->addReference($reference, $data);

        return $data;
    }
}
