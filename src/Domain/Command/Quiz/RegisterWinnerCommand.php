<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\User;

class RegisterWinnerCommand
{
    /**
     * @var Quiz
     */
    protected $quiz;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var int
     */
    protected $trickCount;

    /**
     * @param Quiz $quiz
     * @param User $user
     * @param string|null $comment
     * @param int $trickCount
     */
    function __construct(Quiz $quiz, User $user, ?string $comment, int $trickCount)
    {
        $this->quiz = $quiz;
        $this->user = $user;
        $this->comment = $comment;
        $this->trickCount = $trickCount;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): Quiz
    {
        return $this->quiz;
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
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return int
     */
    public function getTrickCount(): int
    {
        return $this->trickCount;
    }
}
