<?php

namespace App\Service\Handler\Quiz;

use App\Domain\Command\Quiz\CheckResponseCommand;
use App\Repository\Quiz\ResponseRepository;

/**
 * Allows to check if given response exist in specified quiz.
 */
class CheckResponseHandler
{
    /**
     * @var ResponseRepository
     */
    protected $responseRepository;

    /**
     * @param ResponseRepository $responseRepository
     */
    public function __construct(ResponseRepository $responseRepository)
    {
        $this->responseRepository = $responseRepository;
    }

    /**
     * @param CheckResponseCommand $command
     */
    public function handle(CheckResponseCommand $command)
    {
    }
}
