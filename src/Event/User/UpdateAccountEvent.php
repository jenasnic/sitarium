<?php

namespace App\Event\User;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateAccountEvent extends Event
{
    public const UPDATE_ACCOUNT = 'update_account';

    protected User $user;

    protected ?string $password;

    public function __construct(User $user, ?string $password = null)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
