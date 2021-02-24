<?php

namespace App\Service\Tmdb\Adapter;

use App\Model\Tmdb\DisplayableItem;

/**
 * @psalm-template T
 */
interface DisplayableAdapterInterface
{
    /**
     * @psalm-param T $item
     *
     * @param mixed $item
     */
    public function adapt($item): DisplayableItem;

    /**
     * @param mixed $item
     */
    public function support($item): bool;
}
