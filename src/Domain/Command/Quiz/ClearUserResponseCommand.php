<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Quiz;
use App\Entity\User;

class ClearUserResponseCommand
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Quiz
     */
    protected $quiz;

    /**
     * @param User $user
     * @param Quiz $quiz
     */
    public function __construct(User $user, Quiz $quiz)
    {
        $this->user = $user;
        $this->quiz = $quiz;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }
}
