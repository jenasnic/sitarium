<?php

namespace App\Model\Maze;

use App\Entity\Maze\Actor;
use App\Entity\Maze\Movie;

class MazeGraphItem
{
    /**
     * @var Actor|Movie
     */
    private $item;

    /**
     * @var array<MazeGraphItem>
     */
    private array $linkedItems;

    private int $bestPathPosition = 0;

    private int $bestPathSize = 0;

    /**
     * @param Actor|Movie $item
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->linkedItems = [];
    }

    /**
     * @return Actor|Movie
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Actor|Movie $item
     */
    public function setItem($item): void
    {
        $this->item = $item;
    }

    /**
     * @return MazeGraphItem[]|array<MazeGraphItem>
     */
    public function getLinkedItems(): array
    {
        return $this->linkedItems;
    }

    public function addLinkedItem(MazeGraphItem $linkedItem): self
    {
        $this->linkedItems[] = $linkedItem;

        return $this;
    }

    public function getBestPathPosition(): int
    {
        return $this->bestPathPosition;
    }

    public function setBestPathPosition(int $bestPathPosition): void
    {
        $this->bestPathPosition = $bestPathPosition;
    }

    public function getBestPathSize(): int
    {
        return $this->bestPathSize;
    }

    public function setBestPathSize(int $bestPathSize): void
    {
        $this->bestPathSize = $bestPathSize;
    }
}
