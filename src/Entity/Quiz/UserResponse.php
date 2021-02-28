<?php

namespace App\Entity\Quiz;

use App\Entity\User;
use DateTime;
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
     */
    private ?User $user = null;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Response", fetch="EAGER")
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id")
     */
    private ?Response $response = null;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private ?DateTime $date = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}
