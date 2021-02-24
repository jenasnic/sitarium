<?php

namespace App\Event\Quiz;

use Exception;
use Symfony\Contracts\EventDispatcher\Event;

class TmdbLinkErrorEvent extends Event
{
    public const BUILD_TMDB_LINK_ERROR = 'build_tmdb_link_error';

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
