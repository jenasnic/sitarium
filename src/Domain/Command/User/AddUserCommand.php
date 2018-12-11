<?php

namespace App\Domain\Command\User;

use App\Entity\User;

class AddUserCommand
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
    public function __construct(User $user, string $password = null)
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
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
