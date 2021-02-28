<?php

namespace App\Event\Maze;

use Exception;
use Symfony\Contracts\EventDispatcher\Event;

class CastingErrorEvent extends Event
{
    public const BUILD_CASTING_ERROR = 'build_casting_error';

    protected Exception $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getException(): Exception
    {
        return $this->exception;
    }
}
