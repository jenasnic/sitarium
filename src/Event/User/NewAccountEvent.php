<?php

namespace App\Event\User;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class NewAccountEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param User $user
     * @param string $password
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
