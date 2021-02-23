<?php

namespace App\Event\User;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ResetPasswordEvent extends Event
{
    public const RESET_PASSWORD = 'reset_password';

    protected User $user;

    protected string $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
