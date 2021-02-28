<?php

namespace App\Event\Synchronization;

use Exception;
use Symfony\Contracts\EventDispatcher\Event;

class SynchronizationErrorEvent extends Event
{
    public const SYNCHRONIZE_DATA_ERROR = 'synchronize_data_error';

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
