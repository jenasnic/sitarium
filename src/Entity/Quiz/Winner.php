<?php

namespace App\Entity\Quiz;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="quiz_winner")
 * @ORM\Entity(repositoryClass="App\Repository\Quiz\WinnerRepository")
 */
class Winner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Quiz $quiz = null;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private ?DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }
}
