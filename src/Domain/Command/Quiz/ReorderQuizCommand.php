<?php

namespace App\Domain\Command\Quiz;

/**
 * Command to reorder quiz (i.e. update rank property).
 * This command define an array of elements composed as follow :
 *     - 'id' property (identifier of quiz we want to update rank)
 *     - 'rank' property (new order to set for quiz).
 */
class ReorderQuizCommand
{
    protected array $reorderedIds;

    public function __construct(array $reorderedIds)
    {
        $this->reorderedIds = $reorderedIds;
    }

    public function getReorderedIds(): array
    {
        return $this->reorderedIds;
    }
}
