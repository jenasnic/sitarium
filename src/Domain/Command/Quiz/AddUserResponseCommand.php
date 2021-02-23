<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Response;
use App\Entity\User;

class AddUserResponseCommand
{
    protected User $user;

    protected Response $response;

    public function __construct(User $user, Response $response)
    {
        $this->user = $user;
        $this->response = $response;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
