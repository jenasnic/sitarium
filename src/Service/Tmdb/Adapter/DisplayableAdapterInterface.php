<?php

namespace App\Service\Tmdb\Adapter;

use App\Model\Tmdb\DisplayableItem;

interface DisplayableAdapterInterface
{
    public function adapt($item): DisplayableItem;

    public function support($item): bool;
}
