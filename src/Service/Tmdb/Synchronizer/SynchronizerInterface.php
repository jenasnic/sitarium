<?php
namespace App\Service\Tmdb\Synchronizer;

interface SynchronizerInterface
{
    public function synchronize(): int;

    public function support(string $type): bool;
}
