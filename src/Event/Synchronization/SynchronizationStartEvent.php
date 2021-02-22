<?php

namespace App\Event\Synchronization;

use Symfony\Contracts\EventDispatcher\Event;

class SynchronizationStartEvent extends Event
{
    public const SYNCHRONIZE_DATA_START = 'synchronize_data_start';

    /**
     * @var int
     */
    protected $total;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param int $total
     * @param string $entityClass
     */
    public function __construct(int $total, string $entityClass)
    {
        $this->total = $total;
        $this->entityClass = $entityClass;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return string|null
     */
    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }
}
