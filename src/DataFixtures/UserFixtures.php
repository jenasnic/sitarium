<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Tool\PasswordUtil;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = 'user-admin';
    public const USER_REI = 'user-rei';
    public const USER_TED = 'user-ted';
    public const USER_JEANNOT = 'user-jeannot';
    public const USER_JIM = 'user-jim';
    public const USER_DAN = 'user-dan';

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

        $data[] = $this->buildData('JC', '', 'jc@jc.com', 'admin', 'admin', ['ROLE_ADMIN','ROLE_USER'], self::USER_ADMIN);
        $data[] = $this->buildData('Rei', 'Ichido', 'rei.ichido@yopmail.com', 'rei', 'pwd', ['ROLE_USER'], self::USER_REI);
        $data[] = $this->buildData('Ted', 'Reietsu', 'ted.reietsu@yopmail.com', 'ted', 'pwd', ['ROLE_USER'], self::USER_TED);
        $data[] = $this->buildData('Jeannot', 'Schusse', 'jeannot.schusse@yopmail.com', 'jeannot', 'pwd', ['ROLE_USER'], self::USER_JEANNOT);
        $data[] = $this->buildData('Jim', 'Daima', 'jim.daima@yopmail.com', 'jim', 'pwd', ['ROLE_USER'], self::USER_JIM);
        $data[] = $this->buildData('Dan', 'Monohoshi', 'dan.monohoshi@yopmail.com', 'dan', 'pwd', ['ROLE_USER'], self::USER_DAN);

        return $data;
    }

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
