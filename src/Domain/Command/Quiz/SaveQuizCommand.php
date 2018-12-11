<?php

namespace App\Domain\Command\Quiz;

use App\Entity\Quiz\Quiz;

class SaveQuizCommand
{
    /**
     * @var Quiz
     */
    protected $quiz;

    /**
     * @param Quiz $quiz
     */
    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }
}
