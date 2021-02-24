<?php

namespace App\Service\Tmdb;

use App\Service\Tmdb\Synchronizer\SynchronizerInterface;

/**
 * This service allows to synchronize data with TMDB.
 */
class TmdbDataSynchronizer
{
    /**
     * @var SynchronizerInterface[]|iterable<SynchronizerInterface>
     */
    protected iterable $synchronizers;

    public function __construct(iterable $synchronizers)
    {
        $this->synchronizers = $synchronizers;
    }

    public function synchronize(string $type): void
    {
        foreach ($this->synchronizers as $synchronizer) {
            if ($synchronizer->support($type)) {
                $synchronizer->synchronize();
            }
        }
    }
}
