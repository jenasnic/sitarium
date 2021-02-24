<?php

namespace App\Domain\Command\Quiz;

/**
 * Command to reorder quiz (i.e. update rank property).
 * This command define an array of map composed as follow :
 *     - 'id' key : identifier of quiz we want to update rank
 *     - 'rank' key : new order to set for quiz.
 */
class ReorderQuizCommand
{
    /**
     * @var array<array<string, int>>
     */
    protected array $reorderedIds;

    /**
     * @param array<array<string, int>> $reorderedIds
     */
    public function __construct(array $reorderedIds)
    {
        $this->reorderedIds = $reorderedIds;
    }

    /**
     * @return array<array<string, int>>
     */
    public function getReorderedIds(): array
    {
        return $this->reorderedIds;
    }
}
