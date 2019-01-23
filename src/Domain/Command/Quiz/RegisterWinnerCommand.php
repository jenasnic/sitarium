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
     * @param Quiz $quiz
     * @param User $user
     */
    public function __construct(Quiz $quiz, User $user)
    {
        $this->quiz = $quiz;
        $this->user = $user;
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
}
