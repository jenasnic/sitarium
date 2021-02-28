<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\User;

class RegisterWinnerCommand
{
    protected Quiz $quiz;

    protected User $user;

    public function __construct(Quiz $quiz, User $user)
    {
        $this->quiz = $quiz;
        $this->user = $user;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
