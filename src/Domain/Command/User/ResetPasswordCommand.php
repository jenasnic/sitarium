<?php

namespace App\Domain\Command\User;

class ResetPasswordCommand
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
