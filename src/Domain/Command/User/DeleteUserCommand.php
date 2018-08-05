<?php

namespace App\Domain\Command\User;

use App\Entity\User\User;

class DeleteUserCommand
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
