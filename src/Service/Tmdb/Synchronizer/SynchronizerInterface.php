<?php
namespace App\Service\Tmdb\Synchronizer;

interface SynchronizerInterface
{
    /**
     * @return int
     */
    public function synchronize(): int;

    /**
     * @param $type
     *
     * @return bool
     */
    public function support($type): bool;
}
