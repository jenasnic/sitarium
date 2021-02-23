<?php

namespace App\Event\Synchronization;

use Symfony\Contracts\EventDispatcher\Event;

class SynchronizationProgressEvent extends Event
{
    public const SYNCHRONIZE_DATA_PROGRESS = 'synchronize_data_progress';

    protected int $progress;

    public function __construct(int $progress)
    {
        $this->progress = $progress;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }
}
