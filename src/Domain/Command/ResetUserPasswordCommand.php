<?php

namespace App\Domain\Command;

class ResetUserPasswordCommand
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @param int $userId
     */
    function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
