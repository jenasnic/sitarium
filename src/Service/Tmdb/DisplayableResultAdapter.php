<?php

namespace App\Service\Tmdb;

use App\Model\Tmdb\DisplayableItem;
use App\Service\Tmdb\Adapter\DisplayableAdapterInterface;

/**
 * @see DisplayableAdapterInterface
 */
class DisplayableResultAdapter
{
    /**
     * @var DisplayableAdapterInterface<mixed>[]|iterable<DisplayableAdapterInterface<mixed>>
     */
    protected iterable $adapters = [];

    /**
     * @param iterable<DisplayableAdapterInterface<mixed>> $adapters
     */
    public function __construct(iterable $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @param array<mixed> $items
     *
     * @return array<DisplayableItem>
     */
    public function adaptArray(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $adaptedItem = $this->adaptItem($item);
            if (null !== $adaptedItem) {
                $result[] = $this->adaptItem($item);
            }
        }

        return $result;
    }

    /**
     * @param mixed $item
     */
    public function adaptItem($item): ?DisplayableItem
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->support($item)) {
                return $adapter->adapt($item);
            }
        }

        return null;
    }
}
