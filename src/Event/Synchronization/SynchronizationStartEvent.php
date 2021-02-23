<?php

namespace App\Event\Synchronization;

use Symfony\Contracts\EventDispatcher\Event;

class SynchronizationStartEvent extends Event
{
    public const SYNCHRONIZE_DATA_START = 'synchronize_data_start';

    protected int $total;

    protected string $entityClass;

    public function __construct(int $total, string $entityClass)
    {
        $this->total = $total;
        $this->entityClass = $entityClass;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }
}
