<?php

namespace App\Entity\Quiz;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="quiz_user_response")
 * @ORM\Entity(repositoryClass="App\Repository\Quiz\UserResponseRepository")
 */
class UserResponse
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Response", fetch="EAGER")
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id")
     *
     * @var Response
     */
    private $response;

    /**
     * @ORM\Column(name="date", type="datetime")
     *
     * @var \DateTime
     */
    private $date;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
}
