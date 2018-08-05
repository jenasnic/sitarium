<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Response;
use App\Entity\User\User;

class AddUserResponseCommand
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param User $user
     * @param Response $response
     */
    function __construct(User $user, Response $response)
    {
        $this->user = $user;
        $this->response = $response;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
