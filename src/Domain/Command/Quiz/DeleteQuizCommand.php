<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Quiz;

class DeleteQuizCommand
{
    protected Quiz $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }
}
