<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\User;

class ClearUserResponseCommand
{
    protected User $user;

    protected Quiz $quiz;

    public function __construct(User $user, Quiz $quiz)
    {
        $this->user = $user;
        $this->quiz = $quiz;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }
}
