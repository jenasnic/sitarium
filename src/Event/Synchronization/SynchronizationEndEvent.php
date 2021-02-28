<?php

namespace App\Event\Synchronization;

use Symfony\Contracts\EventDispatcher\Event;

class SynchronizationEndEvent extends Event
{
    public const SYNCHRONIZE_DATA_END = 'synchronize_data_end';
}
