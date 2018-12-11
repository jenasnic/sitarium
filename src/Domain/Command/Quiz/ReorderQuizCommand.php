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
    /**
     * @var array
     */
    protected $reorderedIds;

    /**
     * @param array $reorderedIds
     */
    public function __construct(array $reorderedIds)
    {
        $this->reorderedIds = $reorderedIds;
    }

    /**
     * @return array
     */
    public function getReorderedIds(): array
    {
        return $this->reorderedIds;
    }
}
