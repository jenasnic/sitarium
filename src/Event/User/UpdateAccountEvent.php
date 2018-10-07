<?php

namespace App\Event\User;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\User;

class UpdateAccountEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @param User $user
     * @param string|null $password
     */
    public function __construct(User $user, ?string $password)
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
