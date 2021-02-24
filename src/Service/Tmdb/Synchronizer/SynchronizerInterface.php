<?php
namespace App\Service\Tmdb\Synchronizer;

interface SynchronizerInterface
{
    public function synchronize(): void;

    public function support(string $type): bool;
}
