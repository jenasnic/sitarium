<?php

namespace App\Domain\Command\User;

use App\Entity\User;

class AddUserCommand
{
    protected User $user;

    protected ?string $password;

    public function __construct(User $user, string $password = null)
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
