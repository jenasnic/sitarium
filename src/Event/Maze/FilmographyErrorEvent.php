<?php

namespace App\Event\Maze;

use Exception;
use Symfony\Contracts\EventDispatcher\Event;

class FilmographyErrorEvent extends Event
{
    public const BUILD_FILMOGRAPHY_ERROR = 'build_filmography_error';

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
