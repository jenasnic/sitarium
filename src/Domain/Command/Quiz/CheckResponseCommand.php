<?php

namespace App\Domain\Command\Quiz;

class CheckResponseCommand
{
    /**
     * @var int
     */
    protected $quizId;

    /**
     * @var string
     */
    protected $response;

    /**
     * @param int $quizId
     * @param string $response
     */
    function __construct(int $quizId, string $response)
    {
        $this->quizId = $quizId;
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getQuizId(): int
    {
        return $this->quizId;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }
}
